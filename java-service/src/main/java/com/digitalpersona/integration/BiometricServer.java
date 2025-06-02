package com.digitalpersona.integration;

import com.digitalpersona.onetouch.*;
import com.digitalpersona.onetouch.capture.*;

import java.net.*;
import java.io.*;
import java.util.concurrent.*;

public class BiometricServer {
    private static final int PORT = 8080;
    private static ServerSocket serverSocket;
    private static ExecutorService threadPool = Executors.newCachedThreadPool();
    private static DPFPCapture capturer;

    public static void main(String[] args) {
        try {
            initializeFingerprintReader();
            startServer();
        } catch (Exception e) {
            System.err.println("Server error: " + e.getMessage());
            System.exit(1);
        }
    }

    private static void initializeFingerprintReader() {
        capturer = DPFPGlobal.getCaptureFactory().createCapture();
        capturer.setReaderSerialNumber(null); // Use default reader
        capturer.setPriority(DPFPCapturePriority.CAPTURE_PRIORITY_LOW);
    }

    private static void startServer() throws IOException {
        serverSocket = new ServerSocket(PORT);
        System.out.println("Biometric server started on port " + PORT);

        while (true) {
            Socket clientSocket = serverSocket.accept();
            threadPool.submit(new FingerprintHandler(clientSocket, capturer));
        }
    }
}