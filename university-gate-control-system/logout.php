<?php
/**
 * Logout Handler
 * Destroy session and redirect to login
 */

require_once 'config/config.php';
require_once 'config/session.php';

// Log logout action
if (isLoggedIn()) {
    require_once 'config/db.php';
    
    $logSql = "INSERT INTO system_logs (log_type, user_id, action, description, ip_address) 
               VALUES ('auth', :user_id, 'logout', 'User logged out', :ip)";
    dbQuery($logSql, [
        'user_id' => getCurrentUserId(),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ]);
}

// Clear remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

// Logout
logout();

// Redirect to login page
header('Location: login.php');
exit;
?>
