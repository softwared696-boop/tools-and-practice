<?php
require_once __DIR__ . '/../models/CalculationModel.php';

class CalculatorController {
    private $model;
    
    public function __construct() {
        $this->model = new CalculationModel();
    }
    
    public function saveCalculation($data) {
        // Validation
        if (!isset($data['num1']) || !isset($data['num2']) || !isset($data['operator'])) {
            return ['success' => false, 'error' => 'Missing required fields'];
        }
        
        $num1 = filter_var($data['num1'], FILTER_VALIDATE_FLOAT);
        $num2 = filter_var($data['num2'], FILTER_VALIDATE_FLOAT);
        $operator = $data['operator'];
        
        if ($num1 === false || $num2 === false) {
            return ['success' => false, 'error' => 'Invalid numbers provided'];
        }
        
        // Calculate result
        $result = $this->calculate($num1, $num2, $operator);
        
        if ($result === null) {
            return ['success' => false, 'error' => 'Invalid operator'];
        }
        
        // Save to database
        $saved = $this->model->save($num1, $num2, $operator, $result);
        
        if ($saved) {
            return [
                'success' => true,
                'result' => $result,
                'message' => 'Calculation saved successfully'
            ];
        } else {
            return ['success' => false, 'error' => 'Failed to save calculation'];
        }
    }
    
    private function calculate($num1, $num2, $operator) {
        switch($operator) {
            case '+': return $num1 + $num2;
            case '-': return $num1 - $num2;
            case '*': return $num1 * $num2;
            case '/': 
                if ($num2 == 0) {
                    throw new Exception('Division by zero');
                }
                return $num1 / $num2;
            default: return null;
        }
    }
    
    public function getHistory($limit = 50) {
        return $this->model->getHistory($limit);
    }
    
    public function deleteCalculation($id) {
        return $this->model->delete($id);
    }
}
?>