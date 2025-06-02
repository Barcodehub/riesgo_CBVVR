package com.yourcompany.biometric;

import java.net.*;
import java.io.*;
import java.util.concurrent.*;

public class BiometricServer {
    private static final int PORT = 8080;
    private static final int THREAD_POOL_SIZE = 10;
    private static BiometricService biometricService;
    
    public static void main(String[] args) {
        biometricService = new BiometricService();
        ExecutorService executor = Executors.newFixedThreadPool(THREAD_POOL_SIZE);
        
        try (ServerSocket serverSocket = new ServerSocket(PORT)) {
            System.out.println("Biometric server started on port " + PORT);
            
            while (true) {
                Socket clientSocket = serverSocket.accept();
                executor.execute(new ClientHandler(clientSocket, biometricService));
            }
        } catch (IOException e) {
            System.err.println("Server error: " + e.getMessage());
        } finally {
            executor.shutdown();
            biometricService.close();
        }
    }
    
    private static class ClientHandler implements Runnable {
        private final Socket clientSocket;
        private final BiometricService biometricService;
        
        public ClientHandler(Socket socket, BiometricService service) {
            this.clientSocket = socket;
            this.biometricService = service;
        }
        
        @Override
        public void run() {
            try (BufferedReader in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
                 PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {
                
                String inputLine;
                while ((inputLine = in.readLine()) != null) {
                    System.out.println("Received: " + inputLine);
                    
                    if (inputLine.startsWith("REGISTER:")) {
                        String userId = inputLine.substring(9);
                        try {
                            String template = biometricService.enrollFingerprint();
                            out.println(template);
                        } catch (Exception e) {
                            out.println("ERROR: " + e.getMessage());
                        }
                    } else if (inputLine.startsWith("VERIFY:")) {
                        String[] parts = inputLine.split(":");
                        if (parts.length == 3) {
                            try {
                                boolean verified = biometricService.verifyFingerprint(parts[1], parts[2]);
                                out.println(verified ? "VERIFIED" : "NOT_VERIFIED");
                            } catch (Exception e) {
                                out.println("ERROR: " + e.getMessage());
                            }
                        } else {
                            out.println("ERROR: Invalid verify format");
                        }
                    } else if (inputLine.equals("CAPTURE")) {
                        try {
                            String sample = biometricService.captureSample();
                            out.println(sample);
                        } catch (Exception e) {
                            out.println("ERROR: " + e.getMessage());
                        }
                    } else if (inputLine.equals("QUIT")) {
                        break;
                    } else {
                        out.println("ERROR: Unknown command");
                    }
                }
            } catch (IOException e) {
                System.err.println("Client handler error: " + e.getMessage());
            } finally {
                try {
                    clientSocket.close();
                } catch (IOException e) {
                    System.err.println("Error closing client socket: " + e.getMessage());
                }
            }
        }
    }
}