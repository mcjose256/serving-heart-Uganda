<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

$success = $error = '';
$action = $_GET['action'] ?? 'list';
$slideId = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'save_slide') {
        $title = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $buttonText = sanitize($_POST['button_text'] ?? '');
        $buttonLink = sanitize($_POST['button_link'] ?? '');
        $displayOrder = intval($_POST['display_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $slideIdToSave = $_POST['slide_id'] ?? null;
        
        if (empty($title)) {
            $error = 'Title is required.';
        } else {
            // Handle image upload
            $imagePath = $_POST['existing_image'] ?? '';
            
            if (isset($_FILES['slide_image']) && $_FILES['slide_image']['error'] === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $fileType = $_FILES['slide_image']['type'];
                
                if (in_array($fileType, $allowedTypes)) {
                    $uploadDir = '../assets/images/slider/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileName = 'slide-' . time() . '.jpg';
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['slide_image']['tmp_name'], $uploadPath)) {
                        $imagePath = 'assets/images/slider/' . $fileName;
                        
                        // Delete old image if exists
                        if ($slideIdToSave && !empty($_POST['existing_image']) && file_exists('../' . $_POST['existing_image'])) {
                            unlink('../' . $_POST['existing_image']);
                        }
                    }
                } else {
                    $error = 'Invalid image type. Only JPG and PNG allowed.';
                }
            }
            
            if (!$error) {
                try {
                    if ($slideIdToSave) {
                        // Update
                        $sql = "UPDATE slider_images SET title = :title, description = :description, 
                                button_text = :button_text, button_link = :button_link, 
                                display_order = :display_order, is_active = :is_active, 
                                image_path = :image_path WHERE id = :id";
                        executeQuery($pdo, $sql, [
                            'title' => $title,
                            'description' => $description,
                            'button_text' => $buttonText,
                            'button_link' => $buttonLink,
                            'display_order' => $displayOrder,
                            'is_active' => $isActive,
                            'image_path' => $imagePath,
                            'id' => $slideIdToSave
                        ]);
                        $success = 'Slide updated successfully!';
                    } else {
                        // Create
                        if (empty($imagePath)) {
                            $error = 'Image is required for new slide.';
                        } else {
                            $sql = "INSERT INTO slider_images (title, description, button_text, button_link, 
                                    display_order, is_active, image_path, created_by) 
                                    VALUES (:title, :description, :button_text, :button_link, 
                                    :display_order, :is_active, :image_path, :created_by)";
                            executeQuery($pdo, $sql, [
                                'title' => $title,
                                'description' => $description,
                                'button_text' => $buttonText,
                                'button_link' => $buttonLink,
                                'display_order' => $displayOrder,
                                'is_active' => $isActive,
                                'image_path' => $imagePath,
                                'created_by' => $_SESSION['admin_id']
                            ]);
                            $success = 'Slide created successfully!';
                        }
                    }
                } catch (Exception $e) {
                    $error = 'Failed to save slide: ' . $e->getMessage();
                }
            }
        }
    }
    
    if ($formAction === 'delete_slide') {
        $slideIdToDelete = $_POST['slide_id'] ?? null;
        if ($slideIdToDelete) {
            try {
                $slide = fetchOne($pdo, "SELECT image_path FROM slider_images WHERE id = :id", ['id' => $slideIdToDelete]);
                if ($slide && file_exists('../' . $slide['image_path'])) {
                    unlink('../' . $slide['image_path']);
                }
                executeQuery($pdo, "DELETE FROM slider_images WHERE id = :id", ['id' => $slideIdToDelete]);
                $success = 'Slide deleted successfully!';
            } catch (Exception $e) {
                $error = 'Failed to delete slide.';
            }
        }
    }
}

// Fetch slides
$slides = fetchAll($pdo, "SELECT * FROM slider_images ORDER BY display_order ASC");

// Fetch single slide for edit
$slide = null;
if ($action === 'edit' && $slideId) {
    $slide = fetchOne($pdo, "SELECT * FROM slider_images WHERE id = :id", ['id' => $slideId]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Management | SHU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-heart-fill"></i> SHU Admin</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['admin_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header"><h5 class="mb-0">Navigation</h5></div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="posts.php"><i class="bi bi-newspaper"></i> Blog Posts</a></li>
            <li class="nav-item"><a class="nav-link active" href="slider.php"><i class="bi bi-images"></i> Hero Slider</a></li>
            <li class="nav-item"><a class="nav-link" href="team.php"><i class="bi bi-people"></i> Team Members</a></li>
            <li class="nav-item"><a class="nav-link" href="gallery.php"><i class="bi bi-image"></i> Gallery</a></li>
            <li class="nav-item"><a class="nav-link" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
            <li class="nav-item"><a class="nav-link" href="volunteers.php"><i class="bi bi-person-check"></i> Volunteers</a></li>
            <li class="nav-item"><a class="nav-link" href="messages.php"><i class="bi bi-envelope"></i> Messages</a></li>
            <li class="nav-item"><a class="nav-link" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i><?= $success ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= $error ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-images me-2"></i>Hero Slider Management</h2>
                        <p class="text-muted">Manage homepage carousel slides (Recommended: 3 slides)</p>
                    </div>
                    <a href="slider.php?action=create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Slide
                    </a>
                </div>

                <div class="row g-4">
                    <?php foreach ($slides as $s): ?>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <?php if ($s['image_path'] && file_exists('../' . $s['image_path'])): ?>
                                    <img src="../<?= htmlspecialchars($s['image_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title"><?= htmlspecialchars($s['title']) ?></h5>
                                        <span class="badge bg-<?= $s['is_active'] ? 'success' : 'secondary' ?>">
                                            <?= $s['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </div>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($s['description']) ?></p>
                                    <?php if ($s['button_text']): ?>
                                        <p class="small"><strong>Button:</strong> <?= htmlspecialchars($s['button_text']) ?></p>
                                    <?php endif; ?>
                                    <p class="small"><strong>Order:</strong> <?= $s['display_order'] ?></p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="slider.php?action=edit&id=<?= $s['id'] ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteSlide(<?= $s['id'] ?>)">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="mb-4">
                    <a href="slider.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Slides
                    </a>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h4><?= $action === 'create' ? 'Add New Slide' : 'Edit Slide' ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_action" value="save_slide">
                            <?php if ($slide): ?>
                                <input type="hidden" name="slide_id" value="<?= $slide['id'] ?>">
                                <input type="hidden" name="existing_image" value="<?= $slide['image_path'] ?>">
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" class="form-control" name="title" 
                                               value="<?= htmlspecialchars($slide['title'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($slide['description'] ?? '') ?></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Button Text</label>
                                            <input type="text" class="form-control" name="button_text" 
                                                   value="<?= htmlspecialchars($slide['button_text'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Button Link</label>
                                            <input type="text" class="form-control" name="button_link" 
                                                   value="<?= htmlspecialchars($slide['button_link'] ?? '') ?>"
                                                   placeholder="e.g., about.php">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Slide Image * (1920x1080px)</label>
                                        <?php if (isset($slide['image_path']) && $slide['image_path']): ?>
                                            <div class="mb-2">
                                                <img src="../<?= htmlspecialchars($slide['image_path']) ?>" 
                                                     class="img-thumbnail" style="max-width: 100%;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="slide_image" 
                                               accept="image/*" <?= !$slide ? 'required' : '' ?>>
                                        <small class="text-muted">JPG/PNG, Max 2MB, Recommended: 1920x1080px</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control" name="display_order" 
                                               value="<?= $slide['display_order'] ?? 0 ?>" min="0">
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" 
                                               <?= ($slide['is_active'] ?? true) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Active (Show on website)</label>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>Save Slide
                                        </button>
                                        <a href="slider.php" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display:none;">
        <input type="hidden" name="form_action" value="delete_slide">
        <input type="hidden" name="slide_id" id="deleteSlideId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteSlide(id) {
            if (confirm('Delete this slide? The image file will also be removed.')) {
                document.getElementById('deleteSlideId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>