-- University Gate Control System Database Schema
-- Version: 1.0.0
-- MySQL 5.7+

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create Database
CREATE DATABASE IF NOT EXISTS `university_gate_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `university_gate_control`;

-- ============================================
-- USERS & AUTHENTICATION
-- ============================================

CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_type` ENUM('student', 'staff', 'visitor', 'guard', 'admin', 'main_admin') NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(20),
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `profile_photo` VARCHAR(255) DEFAULT NULL,
  `gender` ENUM('male', 'female', 'other') DEFAULT NULL,
  `date_of_birth` DATE DEFAULT NULL,
  `address` TEXT,
  `emergency_contact` VARCHAR(20),
  `emergency_phone` VARCHAR(20),
  `status` ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'active',
  `theme_preference` ENUM('light', 'dark') DEFAULT 'light',
  `last_login` DATETIME DEFAULT NULL,
  `password_reset_token` VARCHAR(100) DEFAULT NULL,
  `password_reset_expires` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_user_type` (`user_type`),
  INDEX `idx_status` (`status`),
  INDEX `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `students` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `student_id` VARCHAR(20) NOT NULL UNIQUE,
  `department` VARCHAR(100),
  `program` VARCHAR(100),
  `year_level` INT(1),
  `enrollment_date` DATE,
  `expected_graduation` DATE,
  `qr_code` VARCHAR(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `staff` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `staff_id` VARCHAR(20) NOT NULL UNIQUE,
  `department` VARCHAR(100),
  `position` VARCHAR(100),
  `employment_type` ENUM('full_time', 'part_time', 'contract', 'temporary'),
  `hire_date` DATE,
  `office_location` VARCHAR(100),
  `qr_code` VARCHAR(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL UNIQUE,
  `role_display` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `permissions` JSON,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_roles` (
  `user_id` INT(11) NOT NULL,
  `role_id` INT(11) NOT NULL,
  `assigned_by` INT(11),
  `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`, `role_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- GATES & ACCESS CONTROL
-- ============================================

CREATE TABLE `gates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `gate_name` VARCHAR(100) NOT NULL,
  `gate_code` VARCHAR(20) NOT NULL UNIQUE,
  `location` VARCHAR(200),
  `status` ENUM('active', 'inactive', 'maintenance') DEFAULT 'active',
  `operating_hours_start` TIME DEFAULT '00:00:00',
  `operating_hours_end` TIME DEFAULT '23:59:59',
  `has_material_scanner` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `access_logs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11),
  `visitor_id` INT(11),
  `gate_id` INT(11) NOT NULL,
  `access_type` ENUM('entry', 'exit') NOT NULL,
  `access_method` ENUM('qr_scan', 'id_card', 'manual', 'face_recognition') DEFAULT 'manual',
  `decision` ENUM('allowed', 'denied', 'pending') NOT NULL,
  `denial_reason` TEXT,
  `handled_by` INT(11),
  `scan_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `photo_capture` VARCHAR(255),
  `notes` TEXT,
  `ip_address` VARCHAR(45),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`handled_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_scan_timestamp` (`scan_timestamp`),
  INDEX `idx_gate_id` (`gate_id`),
  INDEX `idx_decision` (`decision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `security_inspections` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `access_log_id` BIGINT(20),
  `inspector_id` INT(11),
  `bag_check` BOOLEAN DEFAULT FALSE,
  `metal_detector` BOOLEAN DEFAULT FALSE,
  `x_ray_scan` BOOLEAN DEFAULT FALSE,
  `prohibited_items_found` BOOLEAN DEFAULT FALSE,
  `items_description` TEXT,
  `inspection_notes` TEXT,
  `inspection_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`access_log_id`) REFERENCES `access_logs`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`inspector_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- VISITORS MANAGEMENT
-- ============================================

CREATE TABLE `visitors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `visitor_code` VARCHAR(20) NOT NULL UNIQUE,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100),
  `phone` VARCHAR(20) NOT NULL,
  `id_type` ENUM('national_id', 'passport', 'drivers_license', 'other'),
  `id_number` VARCHAR(50),
  `purpose_of_visit` TEXT,
  `visiting_person` VARCHAR(100),
  `visiting_department` VARCHAR(100),
  `vehicle_plate` VARCHAR(20),
  `qr_code` VARCHAR(255),
  `photo` VARCHAR(255),
  `registered_by` INT(11),
  `status` ENUM('pending', 'approved', 'rejected', 'expired', 'checked_in', 'checked_out') DEFAULT 'pending',
  `valid_from` DATETIME,
  `valid_until` DATETIME,
  `check_in_time` DATETIME,
  `check_out_time` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`registered_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_visitor_code` (`visitor_code`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `visitor_access_logs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `visitor_id` INT(11) NOT NULL,
  `gate_id` INT(11) NOT NULL,
  `access_type` ENUM('entry', 'exit') NOT NULL,
  `handled_by` INT(11),
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `notes` TEXT,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`visitor_id`) REFERENCES `visitors`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`handled_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MATERIALS MANAGEMENT
-- ============================================

CREATE TABLE `materials` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `material_code` VARCHAR(50) NOT NULL UNIQUE,
  `material_name` VARCHAR(200) NOT NULL,
  `category` ENUM('laptop', 'computer', 'tablet', 'equipment', 'personal', 'university_asset', 'other') NOT NULL,
  `serial_number` VARCHAR(100),
  `brand` VARCHAR(100),
  `model` VARCHAR(100),
  `value` DECIMAL(10,2),
  `owner_type` ENUM('student', 'staff', 'university', 'visitor') NOT NULL,
  `owner_id` INT(11),
  `description` TEXT,
  `photo` VARCHAR(255),
  `status` ENUM('active', 'inactive', 'reported_lost', 'disposed') DEFAULT 'active',
  `requires_approval` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_material_code` (`material_code`),
  INDEX `idx_category` (`category`),
  INDEX `idx_owner` (`owner_type`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `material_movements` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `material_id` INT(11) NOT NULL,
  `carrier_id` INT(11),
  `visitor_id` INT(11),
  `gate_id` INT(11) NOT NULL,
  `movement_type` ENUM('entry', 'exit') NOT NULL,
  `approval_status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `approved_by` INT(11),
  `approval_timestamp` DATETIME,
  `movement_timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `quantity` INT(11) DEFAULT 1,
  `purpose` TEXT,
  `expected_return_date` DATE,
  `actual_return_date` DATE,
  `handled_by` INT(11),
  `notes` TEXT,
  `photo_evidence` VARCHAR(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`material_id`) REFERENCES `materials`(`id`),
  FOREIGN KEY (`carrier_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`handled_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_movement_timestamp` (`movement_timestamp`),
  INDEX `idx_approval_status` (`approval_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `material_permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `material_category` ENUM('laptop', 'computer', 'tablet', 'equipment', 'personal', 'university_asset', 'other'),
  `permission_type` ENUM('one_time', 'recurring', 'permanent') DEFAULT 'one_time',
  `is_approved` BOOLEAN DEFAULT FALSE,
  `approved_by` INT(11),
  `valid_from` DATE,
  `valid_until` DATE,
  `conditions` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`approved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INCIDENTS MANAGEMENT
-- ============================================

CREATE TABLE `incidents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `incident_code` VARCHAR(20) NOT NULL UNIQUE,
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `category` ENUM('security_breach', 'prohibited_item', 'unauthorized_access', 'material_violation', 'behavioral', 'equipment_malfunction', 'other') NOT NULL,
  `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'low',
  `status` ENUM('reported', 'under_review', 'escalated', 'resolved', 'closed') DEFAULT 'reported',
  `location` VARCHAR(200),
  `gate_id` INT(11),
  `reported_by` INT(11) NOT NULL,
  `assigned_to` INT(11),
  `involved_persons` JSON,
  `evidence_files` JSON,
  `witnesses` JSON,
  `action_taken` TEXT,
  `resolution_notes` TEXT,
  `resolved_by` INT(11),
  `resolved_at` DATETIME,
  `escalated_to` INT(11),
  `escalated_at` DATETIME,
  `escalation_reason` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`reported_by`) REFERENCES `users`(`id`),
  FOREIGN KEY (`assigned_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`resolved_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`escalated_to`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_status` (`status`),
  INDEX `idx_severity` (`severity`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `incident_updates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `incident_id` INT(11) NOT NULL,
  `updated_by` INT(11) NOT NULL,
  `update_type` ENUM('status_change', 'note', 'evidence_added', 'assignment', 'escalation', 'resolution'),
  `update_content` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`incident_id`) REFERENCES `incidents`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SHIFTS & DUTY ROSTER
-- ============================================

CREATE TABLE `shifts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `shift_name` VARCHAR(50) NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  `gate_id` INT(11),
  `is_active` BOOLEAN DEFAULT TRUE,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `duty_roster` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `guard_id` INT(11) NOT NULL,
  `gate_id` INT(11) NOT NULL,
  `shift_id` INT(11),
  `duty_date` DATE NOT NULL,
  `status` ENUM('scheduled', 'completed', 'cancelled', 'missed') DEFAULT 'scheduled',
  `checked_in` DATETIME,
  `checked_out` DATETIME,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`guard_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`gate_id`) REFERENCES `gates`(`id`),
  FOREIGN KEY (`shift_id`) REFERENCES `shifts`(`id`) ON DELETE SET NULL,
  INDEX `idx_duty_date` (`duty_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- NOTIFICATIONS
-- ============================================

CREATE TABLE `notifications` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('info', 'warning', 'error', 'success', 'alert') DEFAULT 'info',
  `priority` ENUM('low', 'normal', 'high', 'urgent') DEFAULT 'normal',
  `is_read` BOOLEAN DEFAULT FALSE,
  `read_at` DATETIME,
  `action_url` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_user_read` (`user_id`, `is_read`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SYSTEM LOGS & AUDIT
-- ============================================

CREATE TABLE `system_logs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `log_type` ENUM('auth', 'action', 'error', 'system', 'security') NOT NULL,
  `user_id` INT(11),
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `table_affected` VARCHAR(50),
  `record_id` INT(11),
  `old_values` JSON,
  `new_values` JSON,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_log_type` (`log_type`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `audit_trail` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT(11) NOT NULL,
  `action` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
  `changed_by` INT(11),
  `old_data` JSON,
  `new_data` JSON,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  INDEX `idx_table_record` (`table_name`, `record_id`),
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DEPARTMENTS
-- ============================================

CREATE TABLE `departments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dept_name` VARCHAR(100) NOT NULL,
  `dept_code` VARCHAR(20) NOT NULL UNIQUE,
  `description` TEXT,
  `head_of_dept` INT(11),
  `location` VARCHAR(200),
  `contact_email` VARCHAR(100),
  `contact_phone` VARCHAR(20),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`head_of_dept`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CONFIGURATION & SETTINGS
-- ============================================

CREATE TABLE `system_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT,
  `setting_type` ENUM('string', 'number', 'boolean', 'json', 'array') DEFAULT 'string',
  `category` VARCHAR(50),
  `description` TEXT,
  `is_public` BOOLEAN DEFAULT FALSE,
  `updated_by` INT(11),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SEED DATA
-- ============================================

-- Insert default roles
INSERT INTO `roles` (`role_name`, `role_display`, `description`, `permissions`) VALUES
('guard', 'Gate Officer', 'Security guard at gate with scan and inspection permissions', 
 '{"gate_scan": true, "view_logs": true, "report_incident": true, "manage_visitors": true, "material_inspection": true}'),
('admin', 'Admin', 'Administrative user with user management and reports access', 
 '{"user_management": true, "view_all_logs": true, "generate_reports": true, "manage_incidents": true, "system_settings": false}'),
('main_admin', 'Main Admin', 'Full system access including admin management', 
 '{"user_management": true, "view_all_logs": true, "generate_reports": true, "manage_incidents": true, "system_settings": true, "admin_management": true, "audit_access": true}'),
('student', 'Student', 'Student portal access for personal logs and permissions', 
 '{"view_own_logs": true, "request_material_permission": true, "view_profile": true}'),
('staff', 'Staff', 'Staff portal with material approval capabilities', 
 '{"view_own_logs": true, "request_material_permission": true, "approve_materials": true, "view_profile": true}');

-- Insert default gates
INSERT INTO `gates` (`gate_name`, `gate_code`, `location`, `status`, `has_material_scanner`) VALUES
('Main Entrance', 'GATE-001', 'University Main Road', 'active', TRUE),
('North Gate', 'GATE-002', 'North Campus', 'active', FALSE),
('South Gate', 'GATE-003', 'Sports Complex Side', 'active', FALSE),
('East Gate', 'GATE-004', 'Engineering Block', 'active', TRUE),
('West Gate', 'GATE-005', 'Residential Area', 'active', FALSE);

-- Insert default admin user (password: admin123 - hashed with PASSWORD_DEFAULT)
-- Note: In production, change this password immediately!
INSERT INTO `users` (`user_type`, `username`, `password_hash`, `email`, `first_name`, `last_name`, `status`, `theme_preference`) VALUES
('main_admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@university.edu', 'System', 'Administrator', 'active', 'light');

-- Insert system settings
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `description`) VALUES
('site_name', 'University Gate Control System', 'string', 'general', 'Name of the university system'),
('site_logo', '/assets/images/logo/university-logo.png', 'string', 'general', 'Path to university logo'),
('max_login_attempts', '5', 'number', 'security', 'Maximum failed login attempts before lockout'),
('session_timeout', '3600', 'number', 'security', 'Session timeout in seconds'),
('require_approval_for_materials', 'true', 'boolean', 'materials', 'Require approval for material movement'),
('default_visitor_validity_hours', '8', 'number', 'visitors', 'Default validity period for visitor passes in hours'),
('enable_face_recognition', 'false', 'boolean', 'security', 'Enable face recognition feature'),
('system_timezone', 'UTC', 'string', 'general', 'System timezone');

-- Create views for common queries
CREATE VIEW `vw_recent_access_logs` AS
SELECT 
    al.id,
    al.access_type,
    al.decision,
    al.scan_timestamp,
    u.first_name,
    u.last_name,
    u.user_type,
    g.gate_name,
    h.first_name as handled_by_first,
    h.last_name as handled_by_last
FROM access_logs al
LEFT JOIN users u ON al.user_id = u.id
LEFT JOIN gates g ON al.gate_id = g.id
LEFT JOIN users h ON al.handled_by = h.id
ORDER BY al.scan_timestamp DESC
LIMIT 100;

CREATE VIEW `vw_daily_summary` AS
SELECT 
    DATE(scan_timestamp) as log_date,
    COUNT(*) as total_access,
    SUM(CASE WHEN decision = 'allowed' THEN 1 ELSE 0 END) as allowed_count,
    SUM(CASE WHEN decision = 'denied' THEN 1 ELSE 0 END) as denied_count,
    COUNT(DISTINCT user_id) as unique_users,
    COUNT(DISTINCT gate_id) as active_gates
FROM access_logs
GROUP BY DATE(scan_timestamp);

COMMIT;
