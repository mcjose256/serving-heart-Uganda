<?php
session_start();

require_once '../includes/db.php';

// Log logout activity
if (isset($_SESSION['admin_id'])) {
    try {
        $logSql = "INSERT INTO activity_logs (admin_id, action, description, ip_address) VALUES (:admin_id, 'logout', 'Admin logged out', :ip)";
        executeQuery($pdo, $logSql, [
            'admin_id' => $_SESSION['admin_id'],
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
    } catch (Exception $e) {
        error_log("Logout log error: " . $e->getMessage());
    }
}

// Clear all session data
$_SESSION = array();

// Destroy session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destroy session
session_destroy();

// Redirect to login with success message
header('Location: login.php?logged_out=1');
exit;
?>