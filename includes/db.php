<?php
/**
 * Database Connection for Serving Hearts-Uganda
 * This file handles the database connection using PDO
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'shu_website');
define('DB_USER', 'root');  // Change for production
define('DB_PASS', '');      // Change for production
define('DB_CHARSET', 'utf8mb4');

// Error reporting (disable in production)
if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') {
    // Development mode
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    define('ENVIRONMENT', 'development');
} else {
    // Production mode
    error_reporting(0);
    ini_set('display_errors', 0);
    define('ENVIRONMENT', 'production');
}

// PDO connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // Optional: Set timezone
    $pdo->exec("SET time_zone = '+03:00'"); // East Africa Time (UTC+3)
    
} catch (PDOException $e) {
    // Log error instead of displaying in production
    if (ENVIRONMENT === 'development') {
        die("Database Connection Failed: " . $e->getMessage());
    } else {
        // Log to file in production
        error_log("Database Error: " . $e->getMessage(), 3, '../logs/db_errors.log');
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }
}

/**
 * Helper function to execute queries safely
 * 
 * @param PDO $pdo Database connection
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return PDOStatement
 */
function executeQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        if (ENVIRONMENT === 'development') {
            die("Query Error: " . $e->getMessage());
        } else {
            error_log("Query Error: " . $e->getMessage(), 3, '../logs/db_errors.log');
            return false;
        }
    }
}

/**
 * Helper function to get single row
 * 
 * @param PDO $pdo Database connection
 * @param string $sql SQL query
 * @param array $params Parameters to bind
 * @return array|false
 */
function fetchOne($pdo, $sql, $params = []) {
    $stmt = executeQuery($pdo, $sql, $params);
    return $stmt ? $stmt->fetch() : false;
}

/**
 * Helper function to get multiple rows
 * 
 * @param PDO $pdo Database connection
 * @param string $sql SQL query
 * @param array $params Parameters to bind
 * @return array
 */
function fetchAll($pdo, $sql, $params = []) {
    $stmt = executeQuery($pdo, $sql, $params);
    return $stmt ? $stmt->fetchAll() : [];
}

/**
 * Sanitize input to prevent XSS
 * 
 * @param string $data Input data
 * @return string
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 * 
 * @param string $email Email to validate
 * @return bool
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Redirect helper
 * 
 * @param string $url URL to redirect to
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Set flash message
 * 
 * @param string $type Message type (success, error, warning, info)
 * @param string $message Message content
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 * 
 * @return array|null
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}