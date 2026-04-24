<?php
/**
 * Database Connection Class
 * Uses PDO for secure database operations
 */

class Database {
    private static $instance = null;
    private $connection;
    
    // Database configuration
    private $host = 'localhost';
    private $db_name = 'university_gate_control';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    
    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->connect();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Establish database connection
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die(json_encode(['success' => false, 'message' => 'Database connection failed']));
        }
    }
    
    /**
     * Get PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

/**
 * Helper functions for database operations
 */

/**
 * Get database connection
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/**
 * Execute a query with parameters
 */
function dbQuery($sql, $params = []) {
    try {
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Fetch single row
 */
function dbFetchOne($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    if ($stmt) {
        return $stmt->fetch();
    }
    return false;
}

/**
 * Fetch all rows
 */
function dbFetchAll($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    if ($stmt) {
        return $stmt->fetchAll();
    }
    return [];
}

/**
 * Insert record and return last insert ID
 */
function dbInsert($table, $data) {
    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = getDB()->prepare($sql);
        $stmt->execute($data);
        return getDB()->lastInsertId();
    } catch (PDOException $e) {
        error_log("Insert failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Update record
 */
function dbUpdate($table, $data, $where, $whereParams = []) {
    try {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $setString = implode(', ', $set);
        $sql = "UPDATE {$table} SET {$setString} WHERE {$where}";
        
        $stmt = getDB()->prepare($sql);
        $params = array_merge($data, $whereParams);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Update failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete record
 */
function dbDelete($table, $where, $params = []) {
    try {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        error_log("Delete failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Count records
 */
function dbCount($table, $where = '', $params = []) {
    $sql = "SELECT COUNT(*) as count FROM {$table}";
    if ($where) {
        $sql .= " WHERE {$where}";
    }
    $result = dbFetchOne($sql, $params);
    return $result ? (int)$result['count'] : 0;
}

/**
 * Check if record exists
 */
function dbExists($table, $where, $params = []) {
    $count = dbCount($table, $where, $params);
    return $count > 0;
}
