<?php
/**
 * Input Validation Functions
 */

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate username (alphanumeric and underscore only)
function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username);
}

// Validate password strength
function validatePassword($password) {
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return false;
    }
    // At least one uppercase, one lowercase, one number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password);
}

// Validate phone number
function validatePhone($phone) {
    return preg_match('/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/', $phone);
}

// Validate date format
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Sanitize string input
function sanitizeString($input) {
    if (is_array($input)) {
        return array_map('sanitizeString', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Sanitize HTML (allow some tags)
function sanitizeHTML($input) {
    $allowed = '<p><br><strong><em><u><ul><ol><li>';
    return strip_tags(trim($input), $allowed);
}

// Clean input array
function cleanInput($data) {
    if (is_array($data)) {
        return array_map('cleanInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validate integer
function validateInteger($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_INT)) {
        return false;
    }
    if ($min !== null && $value < $min) {
        return false;
    }
    if ($max !== null && $value > $max) {
        return false;
    }
    return true;
}

// Validate float/decimal
function validateFloat($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
        return false;
    }
    if ($min !== null && $value < $min) {
        return false;
    }
    if ($max !== null && $value > $max) {
        return false;
    }
    return true;
}

// Validate required field
function validateRequired($value) {
    return !empty($value) || $value === '0';
}

// Validate file upload
function validateFileUpload($file, $allowedTypes = [], $maxSize = null) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($maxSize === null) {
        $maxSize = MAX_FILE_SIZE;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    return true;
}

// Generate CSRF token
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

// Validate CSRF token from POST
function validateCSRF() {
    $token = $_POST['csrf_token'] ?? '';
    return verifyCSRFToken($token);
}

// Get validation errors
function getValidationErrors($errors = []) {
    $html = '';
    foreach ($errors as $error) {
        $html .= '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    }
    return $html;
}
?>
