<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit(json_encode(['error' => 'Unauthorized']));
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT v.*, a.full_name as reviewer_name 
            FROM volunteers v 
            LEFT JOIN admins a ON v.reviewed_by = a.id 
            WHERE v.id = :id";
    $volunteer = fetchOne($pdo, $sql, ['id' => $id]);
    echo json_encode($volunteer);
}