<?php
/**
 * Global Constants
 * Additional constants for the application
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

// Application Paths
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('MODULES_PATH', ROOT_PATH . '/modules');
define('ROLES_PATH', ROOT_PATH . '/roles');
define('ACTIONS_PATH', ROOT_PATH . '/actions');
define('AJAX_PATH', ROOT_PATH . '/ajax');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('API_PATH', ROOT_PATH . '/api');

// Upload Directories
define('PROFILE_PHOTOS_DIR', UPLOAD_DIR . 'profile-photos/');
define('ID_CARDS_DIR', UPLOAD_DIR . 'id-cards/');
define('QR_CODES_DIR', UPLOAD_DIR . 'qr-codes/');
define('VISITOR_PASSES_DIR', UPLOAD_DIR . 'visitor-passes/');
define('EVIDENCE_DIR', UPLOAD_DIR . 'evidence/');
define('MATERIALS_DIR', UPLOAD_DIR . 'materials/');
define('REPORTS_DIR', UPLOAD_DIR . 'reports/');

// Ensure upload directories exist
$uploadDirs = [
    PROFILE_PHOTOS_DIR,
    ID_CARDS_DIR,
    QR_CODES_DIR,
    VISITOR_PASSES_DIR,
    EVIDENCE_DIR,
    MATERIALS_DIR,
    REPORTS_DIR
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// HTTP Status Codes
define('HTTP_OK', 200);
define('HTTP_CREATED', 201);
define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_CONFLICT', 409);
define('HTTP_INTERNAL_ERROR', 500);

// Response Messages
define('MSG_SUCCESS', 'Operation completed successfully');
define('MSG_ERROR', 'An error occurred');
define('MSG_UNAUTHORIZED', 'Unauthorized access');
define('MSG_FORBIDDEN', 'Access denied');
define('MSG_NOT_FOUND', 'Resource not found');
define('MSG_VALIDATION_ERROR', 'Validation failed');

// Gate Status
define('GATE_OPEN', 'open');
define('GATE_CLOSED', 'closed');
define('GATE_MAINTENANCE', 'maintenance');

// User Status
define('USER_ACTIVE', 'active');
define('USER_INACTIVE', 'inactive');
define('USER_SUSPENDED', 'suspended');

// Gender Options
define('GENDER_MALE', 'male');
define('GENDER_FEMALE', 'female');
define('GENDER_OTHER', 'other');

// Department Types
define('DEPT_ACADEMIC', 'academic');
define('DEPT_ADMINISTRATIVE', 'administrative');
define('DEPT_SECURITY', 'security');
define('DEPT_MAINTENANCE', 'maintenance');

// Notification Types
define('NOTIFICATION_INFO', 'info');
define('NOTIFICATION_WARNING', 'warning');
define('NOTIFICATION_ERROR', 'error');
define('NOTIFICATION_SUCCESS', 'success');

// Priority Levels
define('PRIORITY_LOW', 'low');
define('PRIORITY_NORMAL', 'normal');
define('PRIORITY_HIGH', 'high');
define('PRIORITY_URGENT', 'urgent');

// Shift Types
define('SHIFT_MORNING', 'morning');
define('SHIFT_AFTERNOON', 'afternoon');
define('SHIFT_NIGHT', 'night');

// Days of Week
define('DAYS_WEEK', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);

// Months
define('MONTHS', [
    1 => 'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
]);

// Time Intervals (in minutes)
define('INTERVAL_15_MIN', 15);
define('INTERVAL_30_MIN', 30);
define('INTERVAL_60_MIN', 60);

// Scanner Settings
define('SCANNER_TIMEOUT', 5000); // milliseconds
define('SCANNER_RETRY_COUNT', 3);

// Report Formats
define('REPORT_FORMAT_PDF', 'pdf');
define('REPORT_FORMAT_CSV', 'csv');
define('REPORT_FORMAT_XLSX', 'xlsx');

// Email Settings (for future use)
define('EMAIL_FROM', 'noreply@university.edu');
define('EMAIL_FROM_NAME', 'University Gate Control System');
define('EMAIL_REPLY_TO', 'support@university.edu');

// SMS Settings (for future use)
define('SMS_ENABLED', false);

// Backup Settings
define('BACKUP_ENABLED', true);
define('BACKUP_RETENTION_DAYS', 30);
define('BACKUP_DIR', DATABASE_PATH . '/backup/');

// Session Keys
define('SESSION_USER_ID', 'user_id');
define('SESSION_USERNAME', 'username');
define('SESSION_ROLE', 'user_role');
define('SESSION_EMAIL', 'email');

// Cookie Names
define('COOKIE_THEME', 'theme_preference');
define('COOKIE_REMEMBER', 'remember_token');

// API Rate Limiting
define('API_RATE_LIMIT', 100); // requests per minute
define('API_RATE_WINDOW', 60); // seconds

// Debug Mode
define('DEBUG_MODE', false);

// Maintenance Mode
define('MAINTENANCE_MODE', false);

// Version Check URL (for updates)
define('VERSION_CHECK_URL', '');
