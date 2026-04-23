<?php
require_once __DIR__ . '/../includes/error_handler.php';
require_once __DIR__ . '/../controllers/CalculatorController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonError('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    sendJsonError('Invalid input data');
}

$controller = new CalculatorController();
$result = $controller->saveCalculation($input);

if ($result['success']) {
    echo json_encode($result);
} else {
    sendJsonError($result['error'], $result['code'] ?? 400);
}
?>