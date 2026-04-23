<?php
require_once __DIR__ . '/../config/database.php';

class CalculationModel {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function save($num1, $num2, $operator, $result) {
        try {
            $query = "INSERT INTO calculations (num1, num2, operator, result) 
                      VALUES (:num1, :num2, :operator, :result)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':num1' => $num1,
                ':num2' => $num2,
                ':operator' => $operator,
                ':result' => $result
            ]);
            
            return true;
        } catch(PDOException $e) {
            error_log("Save calculation error: " . $e->getMessage());
            return false;
        }
    }
    
    public function getHistory($limit = 50) {
        try {
            $query = "SELECT * FROM calculations ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Get history error: " . $e->getMessage());
            return [];
        }
    }
    
    public function delete($id) {
        try {
            $query = "DELETE FROM calculations WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch(PDOException $e) {
            error_log("Delete calculation error: " . $e->getMessage());
            return false;
        }
    }
}
?>