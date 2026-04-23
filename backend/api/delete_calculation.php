<?php
require_once __DIR__ . '/../includes/error_handler.php';
require_once __DIR__ . '/../controllers/CalculatorController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonError('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id'])) {
    sendJsonError('ID is required');
}

$controller = new CalculatorController();
$result = $controller->deleteCalculation($input['id']);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
} else {
    sendJsonError('Failed to delete calculation');
}
?>