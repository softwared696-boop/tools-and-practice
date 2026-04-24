<?php
/**
 * Application Configuration File
 * Contains global settings, constants, and configuration
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    define('APP_NAME', 'University Gate Control System');
}

// Application Version
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('UTC');

// Session lifetime (in seconds)
define('SESSION_LIFETIME', 3600); // 1 hour

// Security Settings
define('ENABLE_CSRF', true);
define('CSRF_TOKEN_NAME', 'csrf_token');

// Password Settings
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_COST', 12); // bcrypt cost

// Upload Settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');

// Pagination Settings
define('DEFAULT_PER_PAGE', 25);
define('MAX_PER_PAGE', 100);

// Gate Settings
define('DEFAULT_GATE_COUNT', 4);

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to users
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Base URL (auto-detect if not set)
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = dirname($scriptName);
    define('BASE_URL', $protocol . '://' . $host . $basePath);
}

// Asset URLs
define('CSS_URL', BASE_URL . '/assets/css');
define('JS_URL', BASE_URL . '/assets/js');
define('IMAGES_URL', BASE_URL . '/assets/images');
define('UPLOADS_URL', BASE_URL . '/assets/uploads');

// Role Constants
define('ROLE_GUARD', 'guard');
define('ROLE_ADMIN', 'admin');
define('ROLE_MAIN_ADMIN', 'main_admin');
define('ROLE_STUDENT', 'student');
define('ROLE_STAFF', 'staff');
define('ROLE_VISITOR_OFFICER', 'visitor_officer');

// Access Types
define('ACCESS_ENTRY', 'entry');
define('ACCESS_EXIT', 'exit');

// Incident Severity Levels
define('SEVERITY_LOW', 'low');
define('SEVERITY_MEDIUM', 'medium');
define('SEVERITY_HIGH', 'high');
define('SEVERITY_CRITICAL', 'critical');

// Incident Status
define('STATUS_PENDING', 'pending');
define('STATUS_UNDER_REVIEW', 'under_review');
define('STATUS_ESCALATED', 'escalated');
define('STATUS_RESOLVED', 'resolved');
define('STATUS_CLOSED', 'closed');

// Material Types
define('MATERIAL_PERSONAL', 'personal');
define('MATERIAL_UNIVERSITY', 'university');
define('MATERIAL_EQUIPMENT', 'equipment');

// Theme Options
define('THEME_LIGHT', 'light');
define('THEME_DARK', 'dark');

// API Settings
define('API_VERSION', 'v1');
define('API_KEY_HEADER', 'X-API-Key');

// Logging
define('LOG_ACCESS', true);
define('LOG_AUDIT', true);

// Date Formats
define('DATE_FORMAT', 'Y-m-d');
define('TIME_FORMAT', 'H:i:s');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'M d, Y');
define('DISPLAY_DATETIME_FORMAT', 'M d, Y h:i A');

// Cache Settings
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 300); // 5 minutes

// Feature Flags
define('FEATURE_QR_SCAN', true);
define('FEATURE_NOTIFICATIONS', true);
define('FEATURE_REPORTS', true);
define('FEATURE_ANALYTICS', true);
