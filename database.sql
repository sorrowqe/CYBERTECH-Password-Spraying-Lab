-- CYBERTECH SCOOT Database Schema
-- For cybersecurity training purposes only!

-- Create database
CREATE DATABASE IF NOT EXISTS cbrtch_db;
USE cbrtch_db;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create user profiles table with sensitive information
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address VARCHAR(255),
    city VARCHAR(100),
    country VARCHAR(100),
    date_of_birth DATE,
    drivers_license VARCHAR(50),
    ssn VARCHAR(20),
    credit_card VARCHAR(20),
    bank_account VARCHAR(30),
    emergency_contact VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert test user with weak password (MD5 hashed - INTENTIONALLY WEAK!)
INSERT INTO users (username, email, password) VALUES
('JackieCh4n', 'JackieCh4n@cbrtch.com', '0d6790ac6338e89139ff37a1ef3bc97a'); -- password: geminis

-- Insert sample profile data with realistic sensitive information
INSERT INTO user_profiles (user_id, full_name, phone, address, city, country, date_of_birth, drivers_license, ssn, credit_card, bank_account, emergency_contact, notes) VALUES
(1, 'Peeter Meeter', '+372 123 456', 'Kopli 1', 'Tartu linn', 'Eesti', '1996-05-05', 'BAK-355', '5060212345', '5412-2222-2222-2222', '2222222222', 'Minu mams - +372123456789', 'Loodan, et see konto hakki ei saa. Kaardi kehtivus lopeb: 12/2026');

-- Show created user (without password for security)
SELECT id, username, email, created_at FROM users;

-- Show profile data
SELECT u.username, p.full_name, p.city, p.country FROM users u 
LEFT JOIN user_profiles p ON u.id = p.user_id;
