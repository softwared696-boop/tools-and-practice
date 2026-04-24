<?php
/**
 * Input Validation Rules and Functions
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    die('Direct access not permitted');
}

/**
 * Validate email address
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate username (alphanumeric, 3-50 chars)
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
    return preg_match('/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.]?[0-9]{1,4}[-\s\.]?[0-9]{1,9}$/', 
                     preg_replace('/\s+/', '', $phone));
}

/**
 * Validate date format (YYYY-MM-DD)
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validate integer range
 */
function validateInteger($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_INT)) {
        return false;
    }
    
    $intVal = (int)$value;
    
    if ($min !== null && $intVal < $min) {
        return false;
    }
    
    if ($max !== null && $intVal > $max) {
        return false;
    }
    
    return true;
}

/**
 * Validate float/decimal
 */
function validateFloat($value, $min = null, $max = null) {
    if (!filter_var($value, FILTER_VALIDATE_FLOAT)) {
        return false;
    }
    
    $floatVal = (float)$value;
    
    if ($min !== null && $floatVal < $min) {
        return false;
    }
    
    if ($max !== null && $floatVal > $max) {
        return false;
    }
    
    return true;
}

/**
 * Validate required field
 */
function validateRequired($value) {
    if (is_array($value)) {
        return !empty($value);
    }
    return trim($value) !== '';
}

/**
 * Validate string length
 */
function validateStringLength($string, $min = null, $max = null) {
    $len = strlen($string);
    
    if ($min !== null && $len < $min) {
        return false;
    }
    
    if ($max !== null && $len > $max) {
        return false;
    }
    
    return true;
}

/**
 * Validate alphanumeric string
 */
function validateAlphanumeric($string) {
    return ctype_alnum($string);
}

/**
 * Validate URL
 */
function validateUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Validate IP address
 */
function validateIP($ip) {
    return filter_var($ip, FILTER_VALIDATE_IP) !== false;
}

/**
 * Validate file type
 */
function validateFileType($file, $allowedTypes) {
    if (!isset($file['type'])) {
        return false;
    }
    return in_array($file['type'], $allowedTypes);
}

/**
 * Validate file size
 */
function validateFileSize($file, $maxSize) {
    if (!isset($file['size'])) {
        return false;
    }
    return $file['size'] <= $maxSize;
}

/**
 * Validate enum value
 */
function validateEnum($value, $allowedValues) {
    return in_array($value, $allowedValues, true);
}

/**
 * Validate array values
 */
function validateArray($array, $validator, $params = []) {
    if (!is_array($array)) {
        return false;
    }
    
    foreach ($array as $value) {
        if (!$validator($value, ...$params)) {
            return false;
        }
    }
    
    return true;
}

/**
 * Validate student ID format
 */
function validateStudentId($studentId) {
    return preg_match('/^STU-[0-9]{6,8}$/', $studentId) || 
           preg_match('/^[0-9]{6,10}$/', $studentId);
}

/**
 * Validate staff ID format
 */
function validateStaffId($staffId) {
    return preg_match('/^STF-[0-9]{4,6}$/', $staffId) || 
           preg_match('/^[0-9]{4,8}$/', $staffId);
}

/**
 * Validate visitor pass number
 */
function validateVisitorPass($passNumber) {
    return preg_match('/^VIS-[0-9]{6,8}$/', $passNumber);
}

/**
 * Validate material tag
 */
function validateMaterialTag($tag) {
    return preg_match('/^MAT-[0-9]{6,8}$/', $tag);
}

/**
 * Validate incident number
 */
function validateIncidentNumber($number) {
    return preg_match('/^INC-[0-9]{6,8}$/', $number);
}

/**
 * Comprehensive validation for user input
 */
function validateInput($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $ruleSet) {
        $value = $data[$field] ?? null;
        $rulesArray = is_string($ruleSet) ? explode('|', $ruleSet) : $ruleSet;
        
        foreach ($rulesArray as $rule) {
            $ruleParts = explode(':', $rule);
            $ruleName = $ruleParts[0];
            $ruleParams = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
            
            $isValid = true;
            
            switch ($ruleName) {
                case 'required':
                    $isValid = validateRequired($value);
                    break;
                case 'email':
                    $isValid = validateEmail($value);
                    break;
                case 'username':
                    $isValid = validateUsername($value);
                    break;
                case 'password':
                    $isValid = validatePassword($value);
                    break;
                case 'phone':
                    $isValid = validatePhone($value);
                    break;
                case 'date':
                    $isValid = validateDate($value);
                    break;
                case 'integer':
                    $isValid = validateInteger($value, 
                        isset($ruleParams[0]) ? (int)$ruleParams[0] : null,
                        isset($ruleParams[1]) ? (int)$ruleParams[1] : null);
                    break;
                case 'min':
                    $isValid = strlen($value) >= (int)$ruleParams[0];
                    break;
                case 'max':
                    $isValid = strlen($value) <= (int)$ruleParams[0];
                    break;
                case 'length':
                    $isValid = validateStringLength($value, 
                        isset($ruleParams[0]) ? (int)$ruleParams[0] : null,
                        isset($ruleParams[1]) ? (int)$ruleParams[1] : null);
                    break;
                case 'alphanumeric':
                    $isValid = validateAlphanumeric($value);
                    break;
                case 'url':
                    $isValid = validateUrl($value);
                    break;
                case 'enum':
                    $isValid = validateEnum($value, $ruleParams);
                    break;
                case 'custom':
                    if (isset($ruleParams[0]) && function_exists($ruleParams[0])) {
                        $isValid = call_user_func($ruleParams[0], $value);
                    }
                    break;
            }
            
            if (!$isValid) {
                $errorKey = $field . '.' . $ruleName;
                $errors[$errorKey] = "Validation failed for {$field}: {$ruleName}";
                break; // Stop on first error for this field
            }
        }
    }
    
    return empty($errors) ? true : $errors;
}

/**
 * Get validation error message
 */
function getValidationError($field, $rule) {
    $messages = [
        'required' => "{$field} is required",
        'email' => "Please enter a valid email address",
        'username' => "Username must be 3-50 characters, alphanumeric only",
        'password' => "Password must be at least " . PASSWORD_MIN_LENGTH . " characters with uppercase, lowercase, and number",
        'phone' => "Please enter a valid phone number",
        'date' => "Please enter a valid date",
        'integer' => "Please enter a valid number",
        'min' => "{$field} must be at least specified length",
        'max' => "{$field} must not exceed specified length",
        'alphanumeric' => "{$field} must contain only letters and numbers",
        'url' => "Please enter a valid URL",
        'enum' => "Invalid selection for {$field}"
    ];
    
    return $messages[$rule] ?? "Invalid {$field}";
}
