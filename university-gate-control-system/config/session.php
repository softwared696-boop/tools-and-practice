<?php
/**
 * Session Management and Security Headers
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Initialize secure session
 */
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_httponly', 1);
        
        // HTTPS cookie settings (if using HTTPS)
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            ini_set('session.cookie_secure', 1);
            ini_set('session.cookie_samesite', 'Strict');
        }
        
        // Set session lifetime
        ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
        session_cache_limiter('private_no_expire');
        
        // Start session
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
        } elseif (time() - $_SESSION['_created'] > 1800) {
            // Regenerate every 30 minutes
            session_regenerate_id(true);
            $_SESSION['_created'] = time();
        }
        
        // Check session timeout
        if (isset($_SESSION['_last_activity']) && 
            (time() - $_SESSION['_last_activity'] > SESSION_LIFETIME)) {
            session_destroy();
            session_start();
        }
        $_SESSION['_last_activity'] = time();
    }
}

/**
 * Set security headers
 */
function setSecurityHeaders() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;");
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    if (!ENABLE_CSRF) {
        return true;
    }
    
    if (empty($token) || empty($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Regenerate CSRF token
 */
function regenerateCSRFToken() {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Get CSRF token input field
 */
function csrfField() {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . generateCSRFToken() . '">';
}

/**
 * Sanitize output
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['user_role'] ?? null;
}

/**
 * Get current user data
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'role' => $_SESSION['user_role'],
        'full_name' => $_SESSION['full_name'] ?? '',
        'profile_photo' => $_SESSION['profile_photo'] ?? ''
    ];
}

/**
 * Require login
 */
function requireLogin() {
    initSession();
    if (!isLoggedIn()) {
        redirectTo(BASE_URL . '/login.php');
    }
}

/**
 * Require specific role
 */
function requireRole($roles) {
    requireLogin();
    
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    
    $userRole = getCurrentUserRole();
    if (!in_array($userRole, $roles)) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Access denied']));
    }
}

/**
 * Redirect to URL
 */
function redirectTo($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Log user activity
 */
function logActivity($action, $details = '') {
    if (!isLoggedIn()) {
        return;
    }
    
    $userId = getCurrentUserId();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $data = [
        'user_id' => $userId,
        'action' => $action,
        'details' => $details,
        'ip_address' => $ipAddress,
        'user_agent' => $userAgent,
        'created_at' => date(DATETIME_FORMAT)
    ];
    
    dbInsert('system_logs', $data);
}

/**
 * Initialize application
 */
function initApp() {
    // Load configuration
    require_once __DIR__ . '/config.php';
    
    // Load database
    require_once __DIR__ . '/db.php';
    
    // Initialize session
    initSession();
    
    // Set security headers
    setSecurityHeaders();
    
    // Load validation functions
    if (file_exists(__DIR__ . '/validation.php')) {
        require_once __DIR__ . '/validation.php';
    }
}
