-- University Gate Control System Database Schema
-- Version: 1.0.0
-- Description: Complete database architecture for gate control system

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create Database
CREATE DATABASE IF NOT EXISTS `university_gate_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `university_gate_control`;

-- ============================================
-- USERS & AUTHENTICATION TABLES
-- ============================================

-- Users table (all user types)
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_type` ENUM('student', 'staff', 'guard', 'admin', 'main_admin', 'visitor_officer') NOT NULL,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `phone` VARCHAR(20),
  `profile_photo` VARCHAR(255) DEFAULT 'default-avatar.png',
  `department_id` INT(11) UNSIGNED,
  `role_id` INT(11) UNSIGNED,
  `is_active` TINYINT(1) DEFAULT 1,
  `is_verified` TINYINT(1) DEFAULT 0,
  `theme_preference` ENUM('light', 'dark') DEFAULT 'light',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL,
  INDEX `idx_user_type` (`user_type`),
  INDEX `idx_username` (`username`),
  INDEX `idx_email` (`email`),
  INDEX `idx_department` (`department_id`),
  INDEX `idx_role` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Students specific table
CREATE TABLE `students` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `student_id` VARCHAR(20) UNIQUE NOT NULL,
  `program` VARCHAR(100),
  `year_level` ENUM('1', '2', '3', '4', '5+') DEFAULT '1',
  `enrollment_status` ENUM('active', 'inactive', 'graduated', 'suspended') DEFAULT 'active',
  `admission_date` DATE,
  `expected_graduation` DATE,
  `qr_code` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_student_id` (`student_id`),
  INDEX `idx_program` (`program`),
  INDEX `idx_enrollment` (`enrollment_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Staff specific table
CREATE TABLE `staff` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `staff_id` VARCHAR(20) UNIQUE NOT NULL,
  `position` VARCHAR(100),
  `employment_type` ENUM('full-time', 'part-time', 'contract', 'temporary') DEFAULT 'full-time',
  `hire_date` DATE,
  `is_faculty` TINYINT(1) DEFAULT 0,
  `qr_code` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_staff_id` (`staff_id`),
  INDEX `idx_position` (`position`),
  INDEX `idx_employment` (`employment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Roles table
CREATE TABLE `roles` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(50) UNIQUE NOT NULL,
  `role_slug` VARCHAR(50) UNIQUE NOT NULL,
  `description` TEXT,
  `permissions` JSON,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_role_slug` (`role_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Departments table
CREATE TABLE `departments` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `dept_name` VARCHAR(100) NOT NULL,
  `dept_code` VARCHAR(20) UNIQUE NOT NULL,
  `description` TEXT,
  `head_of_dept` INT(11) UNSIGNED,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`head_of_dept`) REFERENCES `users`(`id`),
  INDEX `idx_dept_code` (`dept_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens
CREATE TABLE `password_resets` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `is_used` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_token` (`token`),
  INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Remember me tokens
CREATE TABLE `remember_tokens` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `device_info` VARCHAR(255),
  `ip_address` VARCHAR(45),
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- GATE & ACCESS CONTROL TABLES
-- ============================================

-- Gates table
CREATE TABLE `gates` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `gate_name` VARCHAR(100) NOT NULL,
  `gate_code` VARCHAR(20) UNIQUE NOT NULL,
  `location` VARCHAR(255),
  `gate_type` ENUM('main', 'secondary', 'emergency', 'service') DEFAULT 'main',
  `is_active` TINYINT(1) DEFAULT 1,
  `operating_hours_start` TIME DEFAULT '06:00:00',
  `operating_hours_end` TIME DEFAULT '22:00:00',
  `max_capacity` INT(11) DEFAULT 1000,
  `current_count` INT(11) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_gate_code` (`gate_code`),
  INDEX `idx_gate_type` (`gate_type`),
  INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Access logs table
CREATE TABLE `access_logs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED,
  `visitor_id` INT(11) UNSIGNED,
  `gate_id` INT(11) UNSIGNED NOT NULL,
  `guard_id` INT(11) UNSIGNED,
  `entry_exit` ENUM('entry', 'exit') NOT NULL,
  `access_type` ENUM('student', 'staff', 'visitor', 'material') NOT NULL,
  `scan_method` ENUM('qr_code', 'id_card', 'manual', 'face_recognition') DEFAULT 'qr_code',
  `status` ENUM('allowed', 'denied', 'pending') DEFAULT 'pending',
  `denial_reason` TEXT,
  `inspection_passed` TINYINT(1) DEFAULT 0,
  `has_materials` TINYINT(1) DEFAULT 0,
  `temperature` DECIMAL(4,1),
  `notes` TEXT,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ip_address` VARCHAR(45),
  `device_info` VARCHAR(255),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`guard_id`) REFERENCES `users`(`id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_gate` (`gate_id`),
  INDEX `idx_user` (`user_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_access_type` (`access_type`),
  INDEX `idx_entry_exit` (`entry_exit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Security inspection checklist
CREATE TABLE `security_inspections` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `access_log_id` BIGINT(20) UNSIGNED NOT NULL,
  `bag_checked` TINYINT(1) DEFAULT 0,
  `id_verified` TINYINT(1) DEFAULT 0,
  `prohibited_items` TINYINT(1) DEFAULT 0,
  `temperature_check` TINYINT(1) DEFAULT 0,
  `additional_notes` TEXT,
  `inspector_id` INT(11) UNSIGNED,
  `inspection_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`access_log_id`) REFERENCES `access_logs`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`inspector_id`) REFERENCES `users`(`id`),
  INDEX `idx_access_log` (`access_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- VISITOR MANAGEMENT TABLES
-- ============================================

-- Visitors table
CREATE TABLE `visitors` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `visitor_number` VARCHAR(20) UNIQUE NOT NULL,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100),
  `phone` VARCHAR(20),
  `id_type` ENUM('national_id', 'passport', 'drivers_license', 'other') DEFAULT 'national_id',
  `id_number` VARCHAR(50),
  `organization` VARCHAR(100),
  `purpose` TEXT,
  `host_user_id` INT(11) UNSIGNED,
  `qr_code` VARCHAR(255),
  `photo` VARCHAR(255),
  `registered_by` INT(11) UNSIGNED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`host_user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`registered_by`) REFERENCES `users`(`id`),
  INDEX `idx_visitor_number` (`visitor_number`),
  INDEX `idx_email` (`email`),
  INDEX `idx_phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Visitor passes table
CREATE TABLE `visitor_passes` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `visitor_id` INT(11) UNSIGNED NOT NULL,
  `pass_number` VARCHAR(20) UNIQUE NOT NULL,
  `pass_type` ENUM('single_entry', 'multiple_entry', 'daily', 'weekly', 'monthly') DEFAULT 'single_entry',
  `valid_from` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `valid_until` TIMESTAMP NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `is_extended` TINYINT(1) DEFAULT 0,
  `extension_count` INT(11) DEFAULT 0,
  `max_entries` INT(11) DEFAULT 1,
  `entries_used` INT(11) DEFAULT 0,
  `issued_by` INT(11) UNSIGNED,
  `approved_by` INT(11) UNSIGNED,
  `status` ENUM('active', 'expired', 'used', 'revoked') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`issued_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`),
  INDEX `idx_pass_number` (`pass_number`),
  INDEX `idx_visitor` (`visitor_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_valid_until` (`valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MATERIALS MANAGEMENT TABLES
-- ============================================

-- Materials table
CREATE TABLE `materials` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `material_tag` VARCHAR(20) UNIQUE NOT NULL,
  `material_name` VARCHAR(100) NOT NULL,
  `material_type` ENUM('laptop', 'computer', 'tablet', 'equipment', 'personal', 'university_asset', 'other') NOT NULL,
  `serial_number` VARCHAR(100),
  `brand` VARCHAR(50),
  `model` VARCHAR(50),
  `value` DECIMAL(10,2),
  `owner_type` ENUM('student', 'staff', 'university', 'visitor') DEFAULT 'student',
  `owner_id` INT(11) UNSIGNED,
  `description` TEXT,
  `photo` VARCHAR(255),
  `qr_code` VARCHAR(255),
  `is_approved` TINYINT(1) DEFAULT 0,
  `approved_by` INT(11) UNSIGNED,
  `approval_date` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`),
  INDEX `idx_material_tag` (`material_tag`),
  INDEX `idx_material_type` (`material_type`),
  INDEX `idx_serial` (`serial_number`),
  INDEX `idx_owner` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Material movement logs
CREATE TABLE `material_movements` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `material_id` INT(11) UNSIGNED NOT NULL,
  `carrier_id` INT(11) UNSIGNED NOT NULL,
  `gate_id` INT(11) UNSIGNED NOT NULL,
  `guard_id` INT(11) UNSIGNED,
  `movement_type` ENUM('entry', 'exit') NOT NULL,
  `permission_verified` TINYINT(1) DEFAULT 0,
  `approval_required` TINYINT(1) DEFAULT 0,
  `approval_status` ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
  `approved_by` INT(11) UNSIGNED,
  `quantity` INT(11) DEFAULT 1,
  `purpose` TEXT,
  `expected_return` TIMESTAMP NULL,
  `actual_return` TIMESTAMP NULL,
  `notes` TEXT,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`material_id`) REFERENCES `materials`(`id`),
  FOREIGN KEY (`carrier_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`guard_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`),
  INDEX `idx_material` (`material_id`),
  INDEX `idx_carrier` (`carrier_id`),
  INDEX `idx_timestamp` (`timestamp`),
  INDEX `idx_movement_type` (`movement_type`),
  INDEX `idx_approval` (`approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Material permissions table
CREATE TABLE `material_permissions` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `material_type` ENUM('laptop', 'computer', 'tablet', 'equipment', 'other') NOT NULL,
  `permission_type` ENUM('one-time', 'recurring', 'permanent') DEFAULT 'one-time',
  `is_approved` TINYINT(1) DEFAULT 0,
  `approved_by` INT(11) UNSIGNED,
  `valid_from` DATE,
  `valid_until` DATE,
  `max_quantity` INT(11) DEFAULT 1,
  `conditions` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`),
  INDEX `idx_user` (`user_id`),
  INDEX `idx_material_type` (`material_type`),
  INDEX `idx_approved` (`is_approved`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INCIDENT MANAGEMENT TABLES
-- ============================================

-- Incidents table
CREATE TABLE `incidents` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `incident_number` VARCHAR(20) UNIQUE NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `incident_type` ENUM('security_breach', 'prohibited_item', 'access_denied', 'material_violation', 'behavioral', 'system_error', 'other') NOT NULL,
  `severity` ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
  `status` ENUM('reported', 'under_review', 'escalated', 'resolved', 'closed') DEFAULT 'reported',
  `location` VARCHAR(255),
  `gate_id` INT(11) UNSIGNED,
  `reported_by` INT(11) UNSIGNED NOT NULL,
  `assigned_to` INT(11) UNSIGNED,
  `involved_users` JSON,
  `evidence` JSON,
  `resolution_notes` TEXT,
  `resolved_by` INT(11) UNSIGNED,
  `resolved_at` TIMESTAMP NULL,
  `escalated_to` INT(11) UNSIGNED,
  `escalated_at` TIMESTAMP NULL,
  `escalation_reason` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`),
  FOREIGN KEY (`resolved_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`escalated_to`) REFERENCES `users`(`id`),
  INDEX `idx_incident_number` (`incident_number`),
  INDEX `idx_status` (`status`),
  INDEX `idx_severity` (`severity`),
  INDEX `idx_type` (`incident_type`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Incident updates/audit trail
CREATE TABLE `incident_updates` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `incident_id` INT(11) UNSIGNED NOT NULL,
  `update_type` ENUM('status_change', 'assignment', 'escalation', 'resolution', 'note') NOT NULL,
  `previous_value` TEXT,
  `new_value` TEXT,
  `updated_by` INT(11) UNSIGNED NOT NULL,
  `update_notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`incident_id`) REFERENCES `incidents`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
  INDEX `idx_incident` (`incident_id`),
  INDEX `idx_update_type` (`update_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- NOTIFICATIONS TABLES
-- ============================================

-- Notifications table
CREATE TABLE `notifications` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('info', 'warning', 'error', 'success', 'alert') DEFAULT 'info',
  `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
  `is_read` TINYINT(1) DEFAULT 0,
  `action_url` VARCHAR(255),
  `related_entity_type` VARCHAR(50),
  `related_entity_id` INT(11),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `read_at` TIMESTAMP NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_user` (`user_id`),
  INDEX `idx_is_read` (`is_read`),
  INDEX `idx_priority` (`priority`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SYSTEM LOGS & AUDIT TABLES
-- ============================================

-- System audit log
CREATE TABLE `audit_logs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT(11) UNSIGNED,
  `action` VARCHAR(100) NOT NULL,
  `table_name` VARCHAR(50),
  `record_id` INT(11),
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_user` (`user_id`),
  INDEX `idx_action` (`action`),
  INDEX `idx_table` (`table_name`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Error logs
CREATE TABLE `error_logs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `error_type` VARCHAR(50),
  `error_message` TEXT NOT NULL,
  `file_path` VARCHAR(255),
  `line_number` INT(11),
  `stack_trace` TEXT,
  `ip_address` VARCHAR(45),
  `user_id` INT(11) UNSIGNED,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_error_type` (`error_type`),
  INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SETTINGS & CONFIGURATION TABLES
-- ============================================

-- System settings
CREATE TABLE `system_settings` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) UNIQUE NOT NULL,
  `setting_value` TEXT,
  `setting_type` ENUM('string', 'number', 'boolean', 'json', 'array') DEFAULT 'string',
  `category` VARCHAR(50),
  `description` TEXT,
  `is_public` TINYINT(1) DEFAULT 0,
  `updated_by` INT(11) UNSIGNED,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`),
  INDEX `idx_setting_key` (`setting_key`),
  INDEX `idx_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Shift schedules for guards
CREATE TABLE `shift_schedules` (
  `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `guard_id` INT(11) UNSIGNED NOT NULL,
  `gate_id` INT(11) UNSIGNED NOT NULL,
  `shift_date` DATE NOT NULL,
  `shift_start` TIME NOT NULL,
  `shift_end` TIME NOT NULL,
  `is_completed` TINYINT(1) DEFAULT 0,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`guard_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  INDEX `idx_guard` (`guard_id`),
  INDEX `idx_gate` (`gate_id`),
  INDEX `idx_date` (`shift_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

-- ============================================
-- INITIAL DATA SEEDING
-- ============================================

-- Insert default roles
INSERT INTO `roles` (`role_name`, `role_slug`, `description`, `permissions`) VALUES
('Gate Officer', 'guard', 'Security guard at gate with scanning and access control permissions', 
 '{"gate_scan": true, "access_control": true, "view_logs": true, "report_incidents": true, "manage_users": false, "manage_settings": false}'),
('Admin', 'admin', 'Administrative user with user management and reporting capabilities', 
 '{"gate_scan": true, "access_control": true, "view_logs": true, "report_incidents": true, "manage_users": true, "manage_settings": false}'),
('Main Admin', 'main_admin', 'System administrator with full access to all features', 
 '{"gate_scan": true, "access_control": true, "view_logs": true, "report_incidents": true, "manage_users": true, "manage_settings": true}'),
('Student', 'student', 'Student with personal access and material permissions', 
 '{"gate_scan": false, "access_control": false, "view_logs": false, "report_incidents": false, "manage_users": false, "manage_settings": false}'),
('Staff', 'staff', 'Staff member with personal access and approval capabilities', 
 '{"gate_scan": false, "access_control": false, "view_logs": false, "report_incidents": false, "manage_users": false, "manage_settings": false}');

-- Insert default admin user (password: admin123)
INSERT INTO `users` (`user_type`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `role_id`, `is_active`, `is_verified`, `theme_preference`) VALUES
('main_admin', 'admin', 'admin@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System', 'Administrator', 3, 1, 1, 'light');

-- Insert default gates
INSERT INTO `gates` (`gate_name`, `gate_code`, `location`, `gate_type`, `is_active`) VALUES
('Main Gate', 'GATE-001', 'University Main Entrance', 'main', 1),
('North Gate', 'GATE-002', 'North Campus Entrance', 'secondary', 1),
('South Gate', 'GATE-003', 'South Campus Entrance', 'secondary', 1),
('Service Gate', 'GATE-004', 'Service & Delivery Entrance', 'service', 1);

-- Insert system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `description`) VALUES
('university_name', 'University of Technology', 'string', 'general', 'Name of the university'),
('system_version', '1.0.0', 'string', 'system', 'Current system version'),
('max_login_attempts', '5', 'number', 'security', 'Maximum login attempts before lockout'),
('session_timeout', '3600', 'number', 'security', 'Session timeout in seconds'),
('enable_qr_scan', '1', 'boolean', 'gate', 'Enable QR code scanning'),
('require_inspection', '1', 'boolean', 'gate', 'Require security inspection for all entries'),
('default_theme', 'light', 'string', 'appearance', 'Default system theme');
