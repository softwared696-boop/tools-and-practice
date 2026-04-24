-- University Gate Control System Database Schema
-- Version 1.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create database
CREATE DATABASE IF NOT EXISTS `university_gate_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `university_gate_control`;

-- =====================================================
-- USERS AND AUTHENTICATION TABLES
-- =====================================================

-- Users table (base table for all user types)
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('guard', 'admin', 'main_admin', 'student', 'staff', 'visitor_officer') NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `gender` ENUM('male', 'female', 'other') DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `address` TEXT,
  `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  `theme_preference` ENUM('light', 'dark') DEFAULT 'light',
  `last_login` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_username` (`username`),
  INDEX `idx_email` (`email`),
  INDEX `idx_role` (`role`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students table
CREATE TABLE `students` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `student_id` VARCHAR(20) NOT NULL UNIQUE,
  `department` VARCHAR(100),
  `year_level` TINYINT DEFAULT 1,
  `enrollment_date` DATE,
  `expected_graduation` DATE,
  `is_active` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Staff table
CREATE TABLE `staff` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `staff_id` VARCHAR(20) NOT NULL UNIQUE,
  `department` VARCHAR(100),
  `position` VARCHAR(100),
  `employee_type` ENUM('full_time', 'part_time', 'contract', 'temporary'),
  `hire_date` DATE,
  `is_active` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_staff_id` (`staff_id`),
  INDEX `idx_department` (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens
CREATE TABLE `password_resets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Remember me tokens
CREATE TABLE `remember_tokens` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- GATES AND ACCESS CONTROL TABLES
-- =====================================================

-- Gates table
CREATE TABLE `gates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `gate_name` VARCHAR(50) NOT NULL,
  `gate_code` VARCHAR(10) NOT NULL UNIQUE,
  `location` VARCHAR(255),
  `status` ENUM('open', 'closed', 'maintenance') DEFAULT 'open',
  `gate_type` ENUM('pedestrian', 'vehicle', 'both') DEFAULT 'both',
  `operating_hours_start` TIME DEFAULT '00:00:00',
  `operating_hours_end` TIME DEFAULT '23:59:59',
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_gate_code` (`gate_code`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Access logs table
CREATE TABLE `access_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `visitor_id` INT UNSIGNED,
  `gate_id` INT UNSIGNED NOT NULL,
  `access_type` ENUM('entry', 'exit') NOT NULL,
  `access_method` ENUM('id_card', 'qr_code', 'manual', 'biometric') DEFAULT 'manual',
  `status` ENUM('allowed', 'denied', 'pending') NOT NULL,
  `denial_reason` TEXT,
  `officer_id` INT UNSIGNED,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `photo_capture` VARCHAR(255),
  `notes` TEXT,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`officer_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_visitor_id` (`visitor_id`),
  INDEX `idx_gate_id` (`gate_id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_access_type` (`access_type`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- VISITORS TABLES
-- =====================================================

-- Visitors table
CREATE TABLE `visitors` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visitor_code` VARCHAR(20) NOT NULL UNIQUE,
  `full_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `phone` VARCHAR(20),
  `id_type` VARCHAR(50),
  `id_number` VARCHAR(50),
  `purpose` TEXT,
  `host_user_id` INT UNSIGNED,
  `company` VARCHAR(100),
  `photo` VARCHAR(255),
  `status` ENUM('pending', 'approved', 'rejected', 'checked_in', 'checked_out', 'expired') DEFAULT 'pending',
  `registered_by` INT UNSIGNED,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`host_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`registered_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_visitor_code` (`visitor_code`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visitor passes table
CREATE TABLE `visitor_passes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `visitor_id` INT UNSIGNED NOT NULL,
  `pass_number` VARCHAR(20) NOT NULL UNIQUE,
  `valid_from` DATETIME NOT NULL,
  `valid_until` DATETIME NOT NULL,
  `access_level` ENUM('general', 'restricted', 'escorted') DEFAULT 'general',
  `zones_allowed` TEXT,
  `is_printed` BOOLEAN DEFAULT FALSE,
  `printed_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE CASCADE,
  INDEX `idx_pass_number` (`pass_number`),
  INDEX `idx_validity` (`valid_from`, `valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- MATERIALS TABLES
-- =====================================================

-- Materials table
CREATE TABLE `materials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_code` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `category` ENUM('laptop', 'computer', 'tablet', 'equipment', 'personal', 'university_asset', 'other') NOT NULL,
  `type` ENUM('personal', 'university', 'equipment') DEFAULT 'personal',
  `serial_number` VARCHAR(100),
  `brand` VARCHAR(50),
  `model` VARCHAR(50),
  `value` DECIMAL(10,2) DEFAULT 0,
  `owner_user_id` INT UNSIGNED,
  `photo` VARCHAR(255),
  `status` ENUM('active', 'inactive', 'lost', 'damaged') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`owner_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_material_code` (`material_code`),
  INDEX `idx_category` (`category`),
  INDEX `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Material movement logs
CREATE TABLE `material_movements` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `material_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `gate_id` INT UNSIGNED NOT NULL,
  `movement_type` ENUM('entry', 'exit') NOT NULL,
  `permission_required` BOOLEAN DEFAULT FALSE,
  `permission_id` INT UNSIGNED,
  `status` ENUM('allowed', 'denied', 'pending') NOT NULL,
  `denial_reason` TEXT,
  `officer_id` INT UNSIGNED,
  `quantity` INT DEFAULT 1,
  `notes` TEXT,
  `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`material_id`) REFERENCES `materials`(`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`permission_id`) REFERENCES `material_permissions`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`officer_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_material_id` (`material_id`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_movement_type` (`movement_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Material permissions table
CREATE TABLE `material_permissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `material_id` INT UNSIGNED,
  `permission_type` ENUM('one_time', 'recurring', 'permanent') DEFAULT 'one_time',
  `purpose` TEXT,
  `valid_from` DATE NOT NULL,
  `valid_until` DATE,
  `approved_by` INT UNSIGNED,
  `approval_status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `approval_notes` TEXT,
  `approved_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`material_id`) REFERENCES `materials`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_approval_status` (`approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- INCIDENTS TABLES
-- =====================================================

-- Incidents table
CREATE TABLE `incidents` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `incident_code` VARCHAR(20) NOT NULL UNIQUE,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'low',
  `status` ENUM('pending', 'under_review', 'escalated', 'resolved', 'closed') DEFAULT 'pending',
  `category` ENUM('security_breach', 'unauthorized_access', 'material_violation', 'behavioral', 'system_error', 'other') NOT NULL,
  `reported_by` INT UNSIGNED NOT NULL,
  `assigned_to` INT UNSIGNED,
  `gate_id` INT UNSIGNED,
  `related_user_id` INT UNSIGNED,
  `related_visitor_id` INT UNSIGNED,
  `location` VARCHAR(255),
  `evidence_files` TEXT,
  `resolution_notes` TEXT,
  `resolved_by` INT UNSIGNED,
  `resolved_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`related_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`related_visitor_id`) REFERENCES `visitors`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`resolved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_incident_code` (`incident_code`),
  INDEX `idx_severity` (`severity`),
  INDEX `idx_status` (`status`),
  INDEX `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Incident updates/escalations log
CREATE TABLE `incident_updates` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `incident_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `update_type` ENUM('comment', 'status_change', 'escalation', 'assignment', 'resolution') NOT NULL,
  `message` TEXT NOT NULL,
  `old_value` TEXT,
  `new_value` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`incident_id`) REFERENCES `incidents`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  INDEX `idx_incident_id` (`incident_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- NOTIFICATIONS TABLES
-- =====================================================

-- Notifications table
CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('info', 'warning', 'error', 'success') DEFAULT 'info',
  `priority` ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
  `is_read` BOOLEAN DEFAULT FALSE,
  `read_at` DATETIME DEFAULT NULL,
  `action_url` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_is_read` (`is_read`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SYSTEM LOGS AND AUDIT TABLES
-- =====================================================

-- System logs table
CREATE TABLE `system_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED,
  `action` VARCHAR(100) NOT NULL,
  `details` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_action` (`action`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Audit trail table
CREATE TABLE `audit_trail` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT UNSIGNED NOT NULL,
  `action` ENUM('insert', 'update', 'delete') NOT NULL,
  `old_values` TEXT,
  `new_values` TEXT,
  `user_id` INT UNSIGNED,
  `ip_address` VARCHAR(45),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_table_name` (`table_name`),
  INDEX `idx_record_id` (`record_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SETTINGS AND CONFIGURATION TABLES
-- =====================================================

-- System settings table
CREATE TABLE `system_settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `setting_type` ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
  `description` VARCHAR(255),
  `updated_by` INT UNSIGNED,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Departments table
CREATE TABLE `departments` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `code` VARCHAR(20) NOT NULL UNIQUE,
  `type` ENUM('academic', 'administrative', 'security', 'maintenance') DEFAULT 'administrative',
  `head_user_id` INT UNSIGNED,
  `description` TEXT,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`head_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles and permissions (for future expansion)
CREATE TABLE `roles` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL UNIQUE,
  `display_name` VARCHAR(100),
  `description` TEXT,
  `permissions` JSON,
  `is_system` BOOLEAN DEFAULT FALSE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SEED DATA
-- =====================================================

-- Insert default main admin user
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `full_name`, `status`, `theme_preference`) VALUES
('admin', 'admin@university.edu', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/X4.G.2f5f5f5f5f5f', 'main_admin', 'System Administrator', 'active', 'light');

-- Note: Default password is 'admin123' - change immediately after first login
-- Password hash generated with: password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12])

-- Insert default gates
INSERT INTO `gates` (`gate_name`, `gate_code`, `location`, `status`, `gate_type`) VALUES
('Main Gate', 'GATE-001', 'University Main Entrance', 'open', 'both'),
('North Gate', 'GATE-002', 'North Campus Entrance', 'open', 'pedestrian'),
('South Gate', 'GATE-003', 'South Campus Entrance', 'open', 'vehicle'),
('East Gate', 'GATE-004', 'East Campus Entrance', 'open', 'pedestrian');

-- Insert system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `description`) VALUES
('site_name', 'University Gate Control System', 'string', 'Application name'),
('site_logo', '', 'string', 'Site logo URL'),
('enable_qr_scan', 'true', 'boolean', 'Enable QR code scanning'),
('enable_notifications', 'true', 'boolean', 'Enable system notifications'),
('session_timeout', '3600', 'number', 'Session timeout in seconds'),
('max_login_attempts', '5', 'number', 'Maximum login attempts before lockout'),
('require_inspection', 'true', 'boolean', 'Require security inspection for entry');

COMMIT;
