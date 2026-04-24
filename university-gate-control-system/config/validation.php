<?php
/**
 * Input Validation Functions
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Validate email address
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate username (alphanumeric and underscore only)
 */
function validateUsername($username) {
    return preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username);
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return false;
    }
    
    // At least one uppercase, one lowercase, one number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password);
}

/**
 * Validate phone number
 */
function validatePhone($phone) {
    return preg_match('/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/', $phone);
}

/**
 * Validate ID number
 */
function validateIdNumber($idNumber) {
    return preg_match('/^[a-zA-Z0-9\-]{5,20}$/', $idNumber);
}

/**
 * Validate date format
 */
function validateDate($date, $format = 'Y-m-d') {
    $dateTime = DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
}

/**
 * Validate datetime format
 */
function validateDateTime($datetime, $format = 'Y-m-d H:i:s') {
    $dateTime = DateTime::createFromFormat($format, $datetime);
    return $dateTime && $dateTime->format($format) === $datetime;
}

/**
 * Sanitize string input
 */
function sanitizeString($input) {
    if (is_array($input)) {
        return array_map('sanitizeString', $input);
    }
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Sanitize integer input
 */
function sanitizeInteger($input, $default = 0) {
    return filter_var($input, FILTER_VALIDATE_INT) !== false 
        ? (int)$input 
        : $default;
}

/**
 * Sanitize float input
 */
function sanitizeFloat($input, $default = 0.0) {
    return filter_var($input, FILTER_VALIDATE_FLOAT) !== false 
        ? (float)$input 
        : $default;
}

/**
 * Validate required field
 */
function validateRequired($value) {
    if (is_string($value)) {
        return trim($value) !== '';
    }
    return $value !== null && $value !== '';
}

/**
 * Validate string length
 */
function validateStringLength($string, $min, $max) {
    $length = mb_strlen($string);
    return $length >= $min && $length <= $max;
}

/**
 * Validate numeric range
 */
function validateRange($value, $min, $max) {
    return is_numeric($value) && $value >= $min && $value <= $max;
}

/**
 * Validate against allowed values
 */
function validateInArray($value, $allowed) {
    return in_array($value, $allowed, true);
}

/**
 * Validate file upload
 */
function validateFileUpload($file, $allowedTypes = [], $maxSize = null) {
    if (!isset($file['error']) || !is_int($file['error'])) {
        return ['valid' => false, 'message' => 'Unknown upload error'];
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['valid' => false, 'message' => getUploadErrorMessage($file['error'])];
    }
    
    if ($maxSize === null) {
        $maxSize = MAX_UPLOAD_SIZE;
    }
    
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'message' => 'File size exceeds maximum allowed'];
    }
    
    if (!empty($allowedTypes)) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['valid' => false, 'message' => 'Invalid file type'];
        }
    }
    
    return ['valid' => true, 'message' => 'File is valid'];
}

/**
 * Get upload error message
 */
function getUploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the upload'
    ];
    
    return $errors[$errorCode] ?? 'Unknown upload error';
}

/**
 * Validate CSRF token from POST
 */
function validateCSRF() {
    $token = $_POST[CSRF_TOKEN_NAME] ?? '';
    return verifyCSRFToken($token);
}

/**
 * Validate form data with rules
 */
function validateForm($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $ruleSet) {
        $value = $data[$field] ?? null;
        
        foreach ($ruleSet as $rule) {
            $ruleParts = explode(':', $rule);
            $ruleName = $ruleParts[0];
            $ruleParam = $ruleParts[1] ?? null;
            
            switch ($ruleName) {
                case 'required':
                    if (!validateRequired($value)) {
                        $errors[$field][] = ucfirst($field) . ' is required';
                    }
                    break;
                    
                case 'email':
                    if (!empty($value) && !validateEmail($value)) {
                        $errors[$field][] = 'Invalid email address';
                    }
                    break;
                    
                case 'username':
                    if (!empty($value) && !validateUsername($value)) {
                        $errors[$field][] = 'Invalid username format';
                    }
                    break;
                    
                case 'password':
                    if (!empty($value) && !validatePassword($value)) {
                        $errors[$field][] = 'Password must be at least ' . PASSWORD_MIN_LENGTH . ' characters with uppercase, lowercase, and number';
                    }
                    break;
                    
                case 'min_length':
                    if (!empty($value) && strlen($value) < (int)$ruleParam) {
                        $errors[$field][] = ucfirst($field) . ' must be at least ' . $ruleParam . ' characters';
                    }
                    break;
                    
                case 'max_length':
                    if (!empty($value) && strlen($value) > (int)$ruleParam) {
                        $errors[$field][] = ucfirst($field) . ' must not exceed ' . $ruleParam . ' characters';
                    }
                    break;
                    
                case 'numeric':
                    if (!empty($value) && !is_numeric($value)) {
                        $errors[$field][] = ucfirst($field) . ' must be numeric';
                    }
                    break;
                    
                case 'integer':
                    if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                        $errors[$field][] = ucfirst($field) . ' must be an integer';
                    }
                    break;
                    
                case 'in':
                    $allowed = explode(',', $ruleParam);
                    if (!empty($value) && !validateInArray($value, $allowed)) {
                        $errors[$field][] = 'Invalid selection';
                    }
                    break;
            }
        }
    }
    
    return empty($errors) ? true : $errors;
}

/**
 * Get validation errors as flat array
 */
function flattenErrors($errors) {
    $flat = [];
    foreach ($errors as $field => $messages) {
        foreach ($messages as $message) {
            $flat[] = $message;
        }
    }
    return $flat;
}
