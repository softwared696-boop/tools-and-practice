<?php
/**
 * Database Connection Configuration
 * Uses PDO for secure database access
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'university_gate_control');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Create PDO connection
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log error but don't expose details in production
            error_log("Database connection failed: " . $e->getMessage());
            die("Database connection failed. Please contact system administrator.");
        }
    }
    
    return $pdo;
}

// Execute query with parameters
function dbQuery($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Fetch single record
function dbFetchOne($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt->fetch();
}

// Fetch all records
function dbFetchAll($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt->fetchAll();
}

// Insert and return last insert ID
function dbInsert($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

// Update affected rows
function dbUpdate($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt->rowCount();
}

// Delete affected rows
function dbDelete($sql, $params = []) {
    $stmt = dbQuery($sql, $params);
    return $stmt->rowCount();
}

// Begin transaction
function dbBeginTransaction() {
    $pdo = getDBConnection();
    $pdo->beginTransaction();
}

// Commit transaction
function dbCommit() {
    $pdo = getDBConnection();
    $pdo->commit();
}

// Rollback transaction
function dbRollback() {
    $pdo = getDBConnection();
    $pdo->rollBack();
}
