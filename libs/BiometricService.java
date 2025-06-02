package com.yourcompany.biometric;

import com.digitalpersona.onetouch.*;
import com.digitalpersona.onetouch.capture.*;
import com.digitalpersona.onetouch.processing.*;
import com.digitalpersona.onetouch.verification.*;
import java.util.Base64;
import java.util.concurrent.*;

public class BiometricService {
    private DPFPEnrollment enroller;
    private DPFPVerification verificator;
    private DPFPCapture capturer;
    private BlockingQueue<DPFPSample> sampleQueue;
    private boolean isCapturing;
    
    public BiometricService() {
        enroller = DPFPGlobal.getEnrollmentFactory().createEnrollment();
        verificator = DPFPGlobal.getVerificationFactory().createVerification();
        sampleQueue = new LinkedBlockingQueue<>();
        initializeCapture();
    }
    
    private void initializeCapture() {
        try {
            capturer = DPFPGlobal.getCaptureFactory().createCapture();
            capturer.addDataListener(new DPFPDataAdapter() {
                @Override public void dataAcquired(DPFPDataEvent e) {
                    if (isCapturing) {
                        sampleQueue.offer(e.getSample());
                    }
                }
            });
            capturer.startCapture();
        } catch (DPFPException e) {
            System.err.println("Error initializing fingerprint reader: " + e.getMessage());
        }
    }
    
    /**
     * Enroll a new fingerprint
     * @return Base64 encoded template
     * @throws DPFPException if enrollment fails
     */
    public String enrollFingerprint() throws DPFPException {
        enroller.clear();
        isCapturing = true;
        
        try {
            while (enroller.getTemplateStatus() == DPFPEnrollment.Status.INSUFFICIENT) {
                System.out.println("Needed samples: " + enroller.getFeaturesNeeded());
                DPFPFeatureSet features = extractFeatures(sampleQueue.take(), DPFPDataPurpose.DATA_PURPOSE_ENROLLMENT);
                enroller.addFeatures(features);
            }
            
            if (enroller.getTemplateStatus() == DPFPEnrollment.Status.READY) {
                return Base64.getEncoder().encodeToString(enroller.getTemplate().serialize());
            } else {
                throw new DPFPException("Fingerprint enrollment failed");
            }
        } finally {
            isCapturing = false;
        }
    }
    
    /**
     * Verify a fingerprint against a stored template
     * @param fingerprint Base64 encoded template to compare against
     * @param sample Base64 encoded sample to verify
     * @return verification result
     * @throws DPFPException if verification fails
     */
    public boolean verifyFingerprint(String fingerprint, String sample) throws DPFPException {
        DPFPTemplate template = DPFPGlobal.getTemplateFactory().createTemplate();
        template.deserialize(Base64.getDecoder().decode(fingerprint));
        
        DPFPSample sampleObj = DPFPGlobal.getSampleFactory().createSample();
        sampleObj.deserialize(Base64.getDecoder().decode(sample));
        
        DPFPFeatureSet features = extractFeatures(sampleObj, DPFPDataPurpose.DATA_PURPOSE_VERIFICATION);
        DPFPVerificationResult result = verificator.verify(features, template);
        
        return result.isVerified();
    }
    
    /**
     * Capture a single fingerprint sample
     * @return Base64 encoded sample
     * @throws DPFPException if capture fails
     */
    public String captureSample() throws DPFPException {
        isCapturing = true;
        try {
            DPFPSample sample = sampleQueue.take();
            return Base64.getEncoder().encodeToString(sample.serialize());
        } finally {
            isCapturing = false;
        }
    }
    
    private DPFPFeatureSet extractFeatures(DPFPSample sample, DPFPDataPurpose purpose) throws DPFPException {
        DPFPFeatureExtraction extractor = DPFPGlobal.getFeatureExtractionFactory().createFeatureExtraction();
        return extractor.createFeatureSet(sample, purpose);
    }
    
    public void close() {
        if (capturer != null) {
            try {
                capturer.stopCapture();
            } catch (Exception e) {
                System.err.println("Error stopping capture: " + e.getMessage());
            }
        }
    }
}