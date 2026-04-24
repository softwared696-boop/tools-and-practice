<?php
/**
 * Session Management and Security Headers
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    die('Direct access not permitted');
}

// Start session with secure settings
function initSession() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session parameters
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
        
        // Custom session name
        session_name(SESSION_NAME);
        
        // Session lifetime
        ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path' => '/',
            'domain' => '',
            'secure' => false, // Set to true for HTTPS
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        session_start();
        
        // Regenerate session ID periodically
        if (!isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } elseif (time() - $_SESSION['created'] > 1800) { // 30 minutes
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
            session_destroy();
            session_start();
        }
        $_SESSION['last_activity'] = time();
    }
}

// Set security headers
function setSecurityHeaders() {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

// Initialize session
initSession();
setSecurityHeaders();

// CSRF Token generation
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Sanitize input
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Escape output
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Get current user
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    static $user = null;
    if ($user === null) {
        require_once CONFIG_PATH . '/db.php';
        $user = dbFetchOne("SELECT u.*, r.role_slug FROM users u 
                           LEFT JOIN roles r ON u.role_id = r.id 
                           WHERE u.id = ?", [$_SESSION['user_id']]);
    }
    return $user;
}

// Get current user ID
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

// Get current user role
function getCurrentUserRole() {
    return $_SESSION['role_slug'] ?? null;
}

// Check if user has specific role
function hasRole($role) {
    $userRole = getCurrentUserRole();
    if (is_array($role)) {
        return in_array($userRole, $role);
    }
    return $userRole === $role;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

// Redirect if already logged in
function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        $redirectUrl = $_SESSION['redirect_after_login'] ?? getDashboardUrl();
        unset($_SESSION['redirect_after_login']);
        header('Location: ' . $redirectUrl);
        exit;
    }
}

// Get dashboard URL based on role
function getDashboardUrl() {
    $role = getCurrentUserRole();
    switch ($role) {
        case 'guard':
            return BASE_URL . '/roles/guard/dashboard.php';
        case 'admin':
            return BASE_URL . '/roles/admin/dashboard.php';
        case 'main_admin':
            return BASE_URL . '/roles/main_admin/dashboard.php';
        case 'student':
            return BASE_URL . '/roles/student/dashboard.php';
        case 'staff':
            return BASE_URL . '/roles/staff/dashboard.php';
        default:
            return BASE_URL . '/index.php';
    }
}

// Logout user
function logoutUser() {
    // Clear remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        require_once CONFIG_PATH . '/db.php';
        dbDelete("DELETE FROM remember_tokens WHERE token = ?", [$_COOKIE['remember_token']]);
        setcookie('remember_token', '', time() - 3600, '/');
    }
    
    // Destroy session
    $_SESSION = [];
    session_destroy();
    
    // Clear session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Verify password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Generate random token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Log audit trail
function logAudit($action, $tableName = null, $recordId = null, $oldValues = null, $newValues = null) {
    require_once CONFIG_PATH . '/db.php';
    
    $userId = getCurrentUserId();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    dbInsert("INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
             [$userId, $action, $tableName, $recordId, 
              $oldValues ? json_encode($oldValues) : null,
              $newValues ? json_encode($newValues) : null,
              $ipAddress, $userAgent]);
}
