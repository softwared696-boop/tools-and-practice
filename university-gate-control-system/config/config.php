<?php
/**
 * Application Configuration
 * Global constants and settings
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    define('APP_NAME', 'University Gate Control System');
}

// Application version
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('UTC');

// Session settings
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'UGCS_SESSION');

// Security settings
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 900); // 15 minutes
define('PASSWORD_MIN_LENGTH', 8);
define('TOKEN_EXPIRY', 3600); // 1 hour

// File upload settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif']);
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');

// Pagination
define('DEFAULT_PER_PAGE', 20);
define('MAX_PER_PAGE', 100);

// Date formats
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'M d, Y');
define('DISPLAY_DATETIME_FORMAT', 'M d, Y h:i A');

// Application paths
define('BASE_PATH', dirname(__DIR__));
define('CONFIG_PATH', BASE_PATH . '/config');
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('MODULES_PATH', BASE_PATH . '/modules');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('UPLOADS_PATH', BASE_PATH . '/assets/uploads');

// URL paths (adjust based on your server configuration)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = rtrim($basePath, '/');

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('ASSETS_URL', BASE_URL . '/assets');
define('UPLOADS_URL', BASE_URL . '/assets/uploads');

// Error reporting (disable in production)
if (getenv('APP_ENV') === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Default gate ID (can be changed per guard station)
define('DEFAULT_GATE_ID', 1);

// System features
define('ENABLE_QR_SCAN', true);
define('ENABLE_FACE_RECOGNITION', false);
define('REQUIRE_INSPECTION', true);
define('ENABLE_NOTIFICATIONS', true);
