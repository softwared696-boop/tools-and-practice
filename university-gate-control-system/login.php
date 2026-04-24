<?php
/**
 * Login Page
 * University Gate Control System
 */

// Load configuration
require_once 'config/config.php';
require_once 'config/session.php';

// Redirect if already logged in
redirectIfLoggedIn();

// Get any error/success messages from session
$error = $_SESSION['error_message'] ?? '';
$success = $_SESSION['success_message'] ?? '';
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo generateCSRFToken(); ?>">
    <title>Login - University Gate Control System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            padding: var(--spacing-xl);
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
        }
        
        .login-card {
            background-color: var(--bg-primary);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: var(--spacing-2xl);
            border: 1px solid var(--border-color);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: var(--spacing-xl);
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--spacing-md);
            color: var(--primary-color);
        }
        
        .login-title {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }
        
        .login-subtitle {
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
        }
        
        .back-to-home {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--text-secondary);
            font-size: var(--font-size-sm);
            margin-bottom: var(--spacing-lg);
            transition: color var(--transition-fast);
        }
        
        .back-to-home:hover {
            color: var(--primary-color);
        }
        
        .role-selector {
            margin-bottom: var(--spacing-lg);
        }
        
        .role-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--spacing-sm);
            margin-top: var(--spacing-sm);
        }
        
        .role-option {
            padding: var(--spacing-sm);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-md);
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-fast);
            font-size: var(--font-size-sm);
            font-weight: 500;
        }
        
        .role-option:hover {
            border-color: var(--primary-color);
            background-color: rgba(var(--primary-rgb), 0.05);
        }
        
        .role-option.active {
            border-color: var(--primary-color);
            background-color: rgba(var(--primary-rgb), 0.1);
            color: var(--primary-color);
        }
        
        .role-option input {
            display: none;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: var(--spacing-lg) 0;
            color: var(--text-muted);
            font-size: var(--font-size-sm);
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--border-color);
        }
        
        .divider span {
            padding: 0 var(--spacing-md);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-lg);
            font-size: var(--font-size-sm);
        }
        
        .terms-checkbox {
            margin-bottom: var(--spacing-lg);
        }
        
        .terms-checkbox label {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-sm);
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
            cursor: pointer;
        }
        
        .terms-checkbox input {
            margin-top: 2px;
        }
        
        .login-alert {
            margin-bottom: var(--spacing-lg);
        }
        
        @media (max-width: 576px) {
            .login-card {
                padding: var(--spacing-xl);
            }
            
            .role-options {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <a href="index.php" class="back-to-home">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Home
            </a>
            
            <div class="login-card">
                <div class="login-header">
                    <svg class="login-logo" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="80" height="80" rx="16" fill="currentColor"/>
                        <path d="M40 20L56 28V52L40 60L24 52V28L40 20Z" fill="white" opacity="0.9"/>
                        <path d="M40 28L48 32V48L40 52L32 48V32L40 28Z" fill="var(--primary-color)"/>
                    </svg>
                    <h1 class="login-title">Welcome Back</h1>
                    <p class="login-subtitle">Sign in to access your account</p>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger login-alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <?php echo e($error); ?>
                </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                <div class="alert alert-success login-alert">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <?php echo e($success); ?>
                </div>
                <?php endif; ?>
                
                <form id="loginForm" action="actions/auth/login-action.php" method="POST" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-group role-selector">
                        <label class="form-label">Select Your Role</label>
                        <div class="role-options">
                            <label class="role-option active">
                                <input type="radio" name="role" value="guard" checked>
                                Gate Officer
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="admin">
                                Admin
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="main_admin">
                                Main Admin
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="student">
                                Student
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="staff">
                                Staff
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="username">Username or ID</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               placeholder="Enter your username" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" 
                               placeholder="Enter your password" required>
                    </div>
                    
                    <div class="remember-forgot">
                        <label class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" value="1">
                            <span class="form-check-label">Remember me</span>
                        </label>
                        <a href="forgot-password.php" class="text-primary">Forgot Password?</a>
                    </div>
                    
                    <div class="terms-checkbox">
                        <label>
                            <input type="checkbox" name="agree_terms" required>
                            <span>I agree with university rules and system regulations</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        Sign In
                    </button>
                </form>
                
                <div class="divider">
                    <span>or</span>
                </div>
                
                <div class="text-center">
                    <a href="modules/visitors/register.php" class="text-secondary" style="font-size: var(--font-size-sm);">
                        Visitor? Register here
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/app.js"></script>
    <script>
        // Role selection styling
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.role-option').forEach(o => o.classList.remove('active'));
                this.classList.add('active');
                this.querySelector('input').checked = true;
            });
        });
        
        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const termsCheckbox = this.querySelector('input[name="agree_terms"]');
            if (!termsCheckbox.checked) {
                e.preventDefault();
                showToast('You must agree to the terms and conditions', 'warning');
                termsCheckbox.focus();
                return false;
            }
        });
    </script>
</body>
</html>
