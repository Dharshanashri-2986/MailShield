/*=========================================================
  Project : MailShield
  Database : mailshield
  Purpose : Stores every phishing email analysis
=========================================================*/

-- Create Database
CREATE DATABASE IF NOT EXISTS mailshield;

-- Select Database
USE mailshield;

-- ===========================================
-- Table : scan_history
-- ===========================================

CREATE TABLE scan_history
(
    scan_id INT AUTO_INCREMENT PRIMARY KEY,

    input_type ENUM('TEXT','IMAGE') NOT NULL,

    email_text LONGTEXT,

    image_name VARCHAR(255),

    risk_score INT NOT NULL,

    risk_level VARCHAR(20) NOT NULL,

    suspicious_words TEXT,

    detected_urls TEXT,

    detected_emails TEXT,

    analysis_summary TEXT,

    scan_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);