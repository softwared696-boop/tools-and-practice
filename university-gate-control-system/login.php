<?php
/**
 * Login Page
 * Authentication for all user roles
 */

// Start session and load config
require_once 'config/config.php';
require_once 'config/session.php';
require_once 'config/validation.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $redirectUrl = getDashboardUrl(getCurrentUserType());
    header('Location: ' . $redirectUrl);
    exit;
}

// Get flash message
$flashMessage = getFlashMessage();

// Handle form submission
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $userType = $_POST['user_type'] ?? '';
    $remember = isset($_POST['remember']);
    $agreed = isset($_POST['agree_terms']);
    
    // Validation
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } elseif (empty($userType)) {
        $error = 'Please select your role.';
    } elseif (!$agreed) {
        $error = 'You must agree to the university rules and system regulations.';
    } else {
        // Authenticate user
        require_once 'config/db.php';
        
        $sql = "SELECT u.*, s.student_id, st.staff_id 
                FROM users u 
                LEFT JOIN students s ON u.id = s.user_id 
                LEFT JOIN staff st ON u.id = st.user_id 
                WHERE u.username = :username AND u.user_type = :user_type AND u.status = 'active'";
        
        $user = dbFetchOne($sql, ['username' => $username, 'user_type' => $userType]);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['profile_photo'] = $user['profile_photo'];
            $_SESSION['theme_preference'] = $user['theme_preference'] ?? 'light';
            
            if ($user['student_id']) {
                $_SESSION['student_id'] = $user['student_id'];
            }
            if ($user['staff_id']) {
                $_SESSION['staff_id'] = $user['staff_id'];
            }
            
            // Update last login
            dbUpdate("UPDATE users SET last_login = NOW() WHERE id = :id", ['id' => $user['id']]);
            
            // Log the login
            $logSql = "INSERT INTO system_logs (log_type, user_id, action, description, ip_address) 
                       VALUES ('auth', :user_id, 'login', 'User logged in successfully', :ip)";
            dbQuery($logSql, ['user_id' => $user['id'], 'ip' => $_SERVER['REMOTE_ADDR'] ?? '']);
            
            // Remember me functionality
            if ($remember) {
                setcookie('remember_token', bin2hex(random_bytes(32)), time() + (86400 * 30), '/');
            }
            
            // Regenerate session ID for security
            regenerateSession();
            
            // Redirect to appropriate dashboard
            $redirectUrl = getDashboardUrl($user['user_type']);
            
            // Check for redirect after login
            if (isset($_SESSION['redirect_after_login'])) {
                $redirectUrl = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
            }
            
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            $error = 'Invalid username, password, or role.';
            
            // Log failed attempt
            $logSql = "INSERT INTO system_logs (log_type, action, description, ip_address) 
                       VALUES ('auth', 'login_failed', 'Failed login attempt for: ' . sanitizeString($username), :ip)";
            dbQuery($logSql, ['ip' => $_SERVER['REMOTE_ADDR'] ?? '']);
        }
    }
}

function getDashboardUrl($userType) {
    $dashboards = [
        'guard' => 'roles/guard/dashboard.php',
        'admin' => 'roles/admin/dashboard.php',
        'main_admin' => 'roles/main_admin/dashboard.php',
        'student' => 'roles/student/dashboard.php',
        'staff' => 'roles/staff/dashboard.php'
    ];
    
    return $dashboards[$userType] ?? 'index.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Gate Control System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/forms.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img src="assets/images/logo/university-logo.png" alt="Logo" class="login-logo" onerror="this.style.display='none'">
                <h1>University Gate Control</h1>
                <p>Sign in to access your account</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($flashMessage): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flashMessage['type']); ?>">
                    <?php echo htmlspecialchars($flashMessage['message']); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="login-form" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="user_type">Select Role</label>
                    <select id="user_type" name="user_type" required>
                        <option value="">-- Select Your Role --</option>
                        <option value="guard">Gate Officer / Security Guard</option>
                        <option value="admin">Admin</option>
                        <option value="main_admin">Main Admin</option>
                        <option value="student">Student</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="username">Username / ID</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Enter your username or ID"
                           value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter your password">
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        👁️
                    </button>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" <?php echo isset($_POST['remember']) ? 'checked' : ''; ?>>
                        <span>Remember me</span>
                    </label>
                    <a href="forgot-password.php" class="forgot-link">Forgot Password?</a>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label terms-checkbox">
                        <input type="checkbox" name="agree_terms" required 
                               <?php echo isset($_POST['agree_terms']) ? 'checked' : ''; ?>>
                        <span>I agree with university rules and system regulations</span>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>
            
            <div class="login-footer">
                <p><a href="index.php">← Back to Home</a></p>
            </div>
        </div>
    </div>
    
    <script src="assets/js/app.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const userType = document.getElementById('user_type').value;
            if (!userType) {
                e.preventDefault();
                alert('Please select your role.');
                document.getElementById('user_type').focus();
            }
        });
    </script>
</body>
</html>
