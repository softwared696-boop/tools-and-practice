<?php
require_once __DIR__ . '/../includes/error_handler.php';
require_once __DIR__ . '/../controllers/CalculatorController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$controller = new CalculatorController();
$history = $controller->getHistory(50);

echo json_encode([
    'success' => true,
    'data' => $history
]);
?>