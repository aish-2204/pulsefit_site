<?php

/**
 * PulseFit MySQL Database Configuration
 * 
 * IMPORTANT: Uses .env file for credentials - NEVER hardcode sensitive data
 * See .env.example for required environment variables
 */

// Load environment variables from .env file
function load_env_file() {
    $env_file = __DIR__ . '/../.env';
    
    if (!file_exists($env_file)) {
        error_log("ERROR: .env file not found at $env_file");
        return false;
    }
    
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
                (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
                $value = substr($value, 1, -1);
            }
            
            putenv("$key=$value");
        }
    }
    
    return true;
}

// Load environment variables
load_env_file();

// Database connection settings from environment variables
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: 3306);
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'pulsefit_db');

/**
 * Create a MySQLi connection
 * @return mysqli|false Connection object or false on failure
 */
function get_db_connection() {
    try {
        // Set connection timeout to 5 seconds
        ini_set('mysqli.connect_timeout', 5);
        
        $mysqli = new mysqli(
            DB_HOST,
            DB_USER,
            DB_PASS,
            DB_NAME,
            DB_PORT
        );
        
        // Set socket timeout for queries
        $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);

        // Check connection
        if ($mysqli->connect_error) {
            error_log("Database Connection Failed: " . $mysqli->connect_error);
            return false;
        }

        // Set charset to UTF-8
        $mysqli->set_charset("utf8mb4");

        return $mysqli;
    } catch (Exception $e) {
        error_log("Database Connection Exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Close database connection
 * @param mysqli $conn Connection object
 */
function close_db_connection($conn) {
    if ($conn) {
        $conn->close();
    }
}

/**
 * Execute a prepared statement safely
 * @param mysqli $conn Database connection
 * @param string $query SQL query with ? placeholders
 * @param array $params Parameters to bind
 * @param string $types Type string (e.g., 'ssi' for string, string, integer)
 * @return mysqli_result|false Query result or false
 */
function execute_query($conn, $query, $params = [], $types = '') {
    if (!$conn) {
        return false;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        error_log("Prepare Error: " . $conn->error);
        return false;
    }

    if (!empty($params) && !empty($types)) {
        // Create references for bind_param (required in PHP)
        $refs = [];
        foreach ($params as $key => $value) {
            $refs[$key] = &$params[$key];
        }
        if (!$stmt->bind_param($types, ...$refs)) {
            error_log("Bind Error: " . $stmt->error);
            $stmt->close();
            return false;
        }
    }

    if (!$stmt->execute()) {
        error_log("Execute Error: " . $stmt->error);
        $stmt->close();
        return false;
    }

    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}
