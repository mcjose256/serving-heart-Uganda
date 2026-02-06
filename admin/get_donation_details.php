<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit('Unauthorized');
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT d.*, a.full_name as admin_name 
            FROM donations d 
            LEFT JOIN admins a ON d.confirmed_by = a.id 
            WHERE d.id = :id";
    $donation = fetchOne($pdo, $sql, ['id' => $id]);
    echo json_encode($donation);
}