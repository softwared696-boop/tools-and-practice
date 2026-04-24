<?php
/**
 * Application Configuration
 * Global constants and settings
 */

// Application Info
define('APP_NAME', 'University Gate Control System');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/university-gate-control-system');

// Security Settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('PASSWORD_MIN_LENGTH', 8);
define('ENCRYPTION_KEY', 'your-secret-encryption-key-change-in-production');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');

// Pagination
define('DEFAULT_PER_PAGE', 20);
define('MAX_PER_PAGE', 100);

// Timezone
date_default_timezone_set('UTC');

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Include other config files
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session.php';
?>
