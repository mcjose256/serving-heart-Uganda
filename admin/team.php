<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) exit(header('Location: login.php'));
require_once '../includes/db.php';

$success = $error = '';
$action = $_GET['action'] ?? 'list';
$memberId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'save_member') {
        $fullName = sanitize($_POST['full_name'] ?? '');
        $position = sanitize($_POST['position'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $memberIdToSave = $_POST['member_id'] ?? null;
        
        $photoPath = $_POST['existing_photo'] ?? '';
        
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $uploadDir = '../assets/images/team/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $fileName = 'member-' . time() . '.jpg';
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $fileName)) {
                $photoPath = 'assets/images/team/' . $fileName;
            }
        }
        
        try {
            if ($memberIdToSave) {
                $sql = "UPDATE team_members SET full_name = :name, position = :pos, bio = :bio, 
                        email = :email, phone = :phone, display_order = :ord, is_active = :act, 
                        photo_path = :photo WHERE id = :id";
                executeQuery($pdo, $sql, [
                    'name' => $fullName, 'pos' => $position, 'bio' => $bio,
                    'email' => $email, 'phone' => $phone, 'ord' => $displayOrder,
                    'act' => $isActive, 'photo' => $photoPath, 'id' => $memberIdToSave
                ]);
                $success = 'Team member updated!';
            } else {
                $sql = "INSERT INTO team_members (full_name, position, bio, email, phone, 
                        display_order, is_active, photo_path) 
                        VALUES (:name, :pos, :bio, :email, :phone, :ord, :act, :photo)";
                executeQuery($pdo, $sql, [
                    'name' => $fullName, 'pos' => $position, 'bio' => $bio,
                    'email' => $email, 'phone' => $phone, 'ord' => $displayOrder,
                    'act' => $isActive, 'photo' => $photoPath
                ]);
                $success = 'Team member added!';
            }
        } catch (Exception $e) {
            $error = 'Failed to save: ' . $e->getMessage();
        }
    }
    
    if ($formAction === 'delete_member') {
        executeQuery($pdo, "DELETE FROM team_members WHERE id = :id", ['id' => $_POST['member_id']]);
        $success = 'Member deleted!';
    }
}

$members = fetchAll($pdo, "SELECT * FROM team_members ORDER BY display_order ASC");
$member = $action === 'edit' && $memberId ? fetchOne($pdo, "SELECT * FROM team_members WHERE id = :id", ['id' => $memberId]) : null;
?>

<!-- Same HTML structure as slider.php but with team member fields -->