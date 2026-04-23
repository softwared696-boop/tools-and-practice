<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/error.log');

function handleError($errno, $errstr, $errfile, $errline) {
    $errorData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => 'Error',
        'code' => $errno,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline
    ];
    
    logError($errorData);
    sendJsonError('An internal error occurred');
    exit();
}

function handleException($exception) {
    $errorData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => 'Exception',
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ];
    
    logError($errorData);
    sendJsonError('An internal error occurred');
}

function logError($errorData) {
    $logFile = __DIR__ . '/../../logs/error.log';
    $logEntry = json_encode($errorData) . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
    
    // Save to database
    try {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO error_logs (error_message, error_type) VALUES (:message, :type)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':message' => $errorData['message'],
            ':type' => $errorData['type'] ?? 'Unknown'
        ]);
    } catch(Exception $e) {
        // Silently fail if can't log to database
    }
}

function sendJsonError($message, $code = 400) {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'error' => $message
    ]);
}

set_error_handler('handleError');
set_exception_handler('handleException');
?>