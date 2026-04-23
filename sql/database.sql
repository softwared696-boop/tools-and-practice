-- Create database
CREATE DATABASE IF NOT EXISTS calculator_db;
USE calculator_db;

-- Create calculations table
CREATE TABLE IF NOT EXISTS calculations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    num1 DECIMAL(10,2) NOT NULL,
    num2 DECIMAL(10,2) NOT NULL,
    operator VARCHAR(5) NOT NULL,
    result DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_operator (operator)
);

-- Create error logs table
CREATE TABLE IF NOT EXISTS error_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    error_message TEXT NOT NULL,
    error_type VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at)
);

-- Insert sample data (optional)
INSERT INTO calculations (num1, num2, operator, result) VALUES 
(10, 5, '+', 15),
(25, 10, '-', 15),
(6, 7, '*', 42),
(100, 4, '/', 25);