package com.digitalpersona.integration;

import com.digitalpersona.onetouch.*;
import com.digitalpersona.onetouch.processing.*;
import com.digitalpersona.onetouch.capture.*;
import com.digitalpersona.onetouch.capture.event.*;
import com.digitalpersona.onetouch.verification.DPFPVerification;
import com.digitalpersona.onetouch.verification.DPFPVerificationResult;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

import java.io.*;
import java.net.*;
import java.util.Base64;
import java.util.concurrent.CountDownLatch;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.atomic.AtomicBoolean;
import java.util.HashMap;
import java.util.Map;

public class FingerprintHandler implements Runnable {
    private final Socket clientSocket;
    private final DPFPCapture capturer;
    private final DPFPEnrollment enroller;
    private final AtomicBoolean activeCapture;
    private BufferedReader in;
    private BufferedWriter out;

    public FingerprintHandler(Socket socket, DPFPCapture capturer) {
        this.clientSocket = socket;
        this.capturer = capturer;
        this.enroller = DPFPGlobal.getEnrollmentFactory().createEnrollment();
        this.activeCapture = new AtomicBoolean(true);
    }

    @Override
    public void run() {
        try {
            System.out.println("Servidor biométrico listo y esperando eventos...");

            in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
            out = new BufferedWriter(new OutputStreamWriter(clientSocket.getOutputStream()));

            // Leer primer comando (login o ID de usuario)
            String command = in.readLine();
            if (command == null || command.isEmpty()) {
                sendError("Comando inválido");
                return;
            }

            if ("login".equalsIgnoreCase(command)) {
                handleLoginAuthentication();
            } else {
                handleEnrollment(command);
            }

        } catch (Exception e) {
            System.err.println("Error en handler: " + e.getMessage());
        } finally {
            cleanup();
        }
    }

    private void handleLoginAuthentication() throws IOException {
        try {
            // Read templates JSON
            String templatesJson = in.readLine();
            if (templatesJson == null) {
                sendError("No templates received");
                return;
            }

            // Parse templates
            Map<String, String> templates = new Gson().fromJson(templatesJson,
                    new TypeToken<Map<String, String>>(){}.getType());

            // Convert to DPFPTemplate objects
            Map<String, DPFPTemplate> templateObjects = new HashMap<>();
            for (Map.Entry<String, String> entry : templates.entrySet()) {
                try {
                    String templateData = entry.getValue();
                    // Skip if contains protocol markers
                    if (templateData.contains("READY") || templateData.contains("ERROR:")) {
                        System.err.println("Invalid template format for user " + entry.getKey());
                        continue;
                    }

                    byte[] templateBytes = Base64.getDecoder().decode(templateData);
                    DPFPTemplate template = DPFPGlobal.getTemplateFactory().createTemplate();
                    template.deserialize(templateBytes);
                    templateObjects.put(entry.getKey(), template);
                } catch (Exception e) {
                    System.err.println("Error deserializing template for user " + entry.getKey() + ": " + e.getMessage());
                }
            }

            if (templateObjects.isEmpty()) {
                sendError("No valid templates received");
                return;
            }

            activeCapture.set(true);
            out.write("READY\n");
            out.flush();

            // Create verification instance
            DPFPVerification verifier = DPFPGlobal.getVerificationFactory().createVerification();
            verifier.setFARRequested(DPFPVerification.MEDIUM_SECURITY_FAR);

            capturer.addDataListener(new DPFPDataAdapter() {
                @Override
                public void dataAcquired(DPFPDataEvent e) {
                    if (!activeCapture.get()) return;

                    try {
                        DPFPFeatureSet features = extractFeatures(e.getSample(),
                                DPFPDataPurpose.DATA_PURPOSE_VERIFICATION);

                        if (features == null) {
                            sendError("Failed to extract features from sample");
                            return;
                        }

                        // Find matching user
                        String userId = null;
                        for (Map.Entry<String, DPFPTemplate> entry : templateObjects.entrySet()) {
                            DPFPVerificationResult result = verifier.verify(features, entry.getValue());
                            if (result.isVerified()) {
                                userId = entry.getKey();
                                break;
                            }
                        }

                        if (userId != null) {
                            out.write(userId + "\nEND\n");
                            out.flush();
                            activeCapture.set(false);
                        } else {
                            sendError("Fingerprint not recognized");
                        }
                    } catch (Exception ex) {
                        System.err.println("Authentication error: " + ex.getMessage());
                        try {
                            sendError("Authentication error: " + ex.getMessage());
                        } catch (IOException ioex) {
                            System.err.println("Error sending error: " + ioex.getMessage());
                        }
                        activeCapture.set(false);
                    }
                }
            });

            capturer.startCapture();

            // Wait for completion with timeout
            long startTime = System.currentTimeMillis();
            while (activeCapture.get() && (System.currentTimeMillis() - startTime) < 120000) {
                try { Thread.sleep(500); } catch (InterruptedException e) {
                    Thread.currentThread().interrupt();
                    break;
                }
            }

        } catch (Exception e) {
            System.err.println("Authentication handler error: " + e.getMessage());
            throw e;
        } finally {
            capturer.stopCapture();
        }
    }

    private String findMatchingUser(DPFPFeatureSet features, Map<String, DPFPTemplate> templates) {
        DPFPVerification verifier = DPFPGlobal.getVerificationFactory().createVerification();

        for (Map.Entry<String, DPFPTemplate> entry : templates.entrySet()) {
            DPFPVerificationResult result = verifier.verify(features, entry.getValue());
            System.out.println("Comparando con user: " + entry.getKey() + " - ¿Coincide? " + result.isVerified());
            if (result.isVerified()) {
                return entry.getKey();
            }
        }
        return null;
    }

    private void handleEnrollment(String userId) throws IOException {
        DPFPDataAdapter dataListener = null;
        try {
            System.out.println("Starting enrollment for user: " + userId);
            out.write("READY\n");
            out.flush();

            // Create a latch to wait for completion
            CountDownLatch completionLatch = new CountDownLatch(1);

            dataListener = new DPFPDataAdapter() {
                @Override
                public void dataAcquired(DPFPDataEvent e) {
                    if (!activeCapture.get()) return;

                    try {
                        System.out.println("Fingerprint sample acquired");
                        DPFPFeatureSet features = extractFeatures(e.getSample(), DPFPDataPurpose.DATA_PURPOSE_ENROLLMENT);
                        if (features == null) {
                            System.out.println("Failed to extract features");
                            sendError("Failed to extract fingerprint features");
                            return;
                        }

                        System.out.println("Adding features to enroller");
                        enroller.addFeatures(features);
                        int needed = enroller.getFeaturesNeeded();
                        System.out.println("Samples needed: " + needed);

                        out.write("PROGRESS:" + needed + "\n");
                        out.flush();

                        if (needed == 0) {
                            System.out.println("Enrollment completed");
                            DPFPTemplate template = enroller.getTemplate();
                            byte[] templateData = template.serialize();
                            System.out.println("Template size: " + templateData.length);

                            String encoded = Base64.getEncoder().encodeToString(templateData);
                            System.out.println("Sending template...");
                            out.write(encoded + "\nEND\n");
                            out.flush();
                            System.out.println("Template sent successfully");
                            activeCapture.set(false);
                            completionLatch.countDown(); // Signal completion
                        }
                    } catch (Exception ex) {
                        System.err.println("Error in enrollment: " + ex.getMessage());
                        ex.printStackTrace();
                        try {
                            sendError("Enrollment error: " + ex.getMessage());
                        } catch (IOException ioex) {
                            System.err.println("Error sending error: " + ioex.getMessage());
                        }
                        activeCapture.set(false);
                        completionLatch.countDown(); // Signal completion even on error
                    }
                }
            };

            capturer.addDataListener(dataListener);
            System.out.println("Starting capture...");
            capturer.startCapture();

            // Wait for completion with timeout (2 minutes)
            try {
                if (!completionLatch.await(2, TimeUnit.MINUTES)) {
                    sendError("Enrollment timeout");
                }
            } catch (InterruptedException e) {
                Thread.currentThread().interrupt();
                sendError("Enrollment interrupted");
            }

        } catch (Exception e) {
            System.err.println("Failed to start enrollment: " + e.getMessage());
            e.printStackTrace();
            throw e;
        } finally {
            capturer.removeDataListener(dataListener);
        }
    }


    private DPFPFeatureSet extractFeatures(DPFPSample sample, DPFPDataPurpose purpose) {
        try {
            DPFPFeatureExtraction extractor = DPFPGlobal.getFeatureExtractionFactory().createFeatureExtraction();
            return extractor.createFeatureSet(sample, purpose);
        } catch (DPFPImageQualityException e) {
            return null;
        }
    }

    private void sendError(String message) throws IOException {
        out.write("ERROR:" + message + "\nEND\n");
        out.flush();
    }

    private void cleanup() {
        activeCapture.set(false);
        try {
            capturer.stopCapture();
            if (in != null) in.close();
            if (out != null) out.close();
            if (clientSocket != null) clientSocket.close();
        } catch (Exception e) {
            System.err.println("Error limpiando recursos: " + e.getMessage());
        }
    }
}