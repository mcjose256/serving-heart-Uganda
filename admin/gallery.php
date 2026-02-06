<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

$success = $error = '';
$action = $_GET['action'] ?? 'list';
$imageId = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    if ($formAction === 'save_image') {
        $title = sanitize($_POST['title'] ?? '');
        $description = sanitize($_POST['description'] ?? '');
        $category = sanitize($_POST['category'] ?? 'general');
        $displayOrder = intval($_POST['display_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $imageIdToSave = $_POST['image_id'] ?? null;
        
        if (empty($title)) {
            $error = 'Title is required.';
        } else {
            // Handle image upload
            $imagePath = $_POST['existing_image'] ?? '';
            
            if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $fileType = $_FILES['gallery_image']['type'];
                
                if (in_array($fileType, $allowedTypes)) {
                    $uploadDir = '../assets/images/gallery/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileName = 'gallery-' . time() . '-' . uniqid() . '.jpg';
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['gallery_image']['tmp_name'], $uploadPath)) {
                        $imagePath = 'assets/images/gallery/' . $fileName;
                        
                        // Delete old image if updating
                        if ($imageIdToSave && !empty($_POST['existing_image']) && file_exists('../' . $_POST['existing_image'])) {
                            unlink('../' . $_POST['existing_image']);
                        }
                    }
                } else {
                    $error = 'Invalid image type. Only JPG and PNG allowed.';
                }
            }
            
            if (!$error) {
                try {
                    if ($imageIdToSave) {
                        // Update existing image
                        $sql = "UPDATE gallery_images SET title = :title, description = :description, 
                                category = :category, display_order = :display_order, is_active = :is_active, 
                                image_path = :image_path WHERE id = :id";
                        executeQuery($pdo, $sql, [
                            'title' => $title,
                            'description' => $description,
                            'category' => $category,
                            'display_order' => $displayOrder,
                            'is_active' => $isActive,
                            'image_path' => $imagePath,
                            'id' => $imageIdToSave
                        ]);
                        $success = 'Image updated successfully!';
                    } else {
                        // Create new image
                        if (empty($imagePath)) {
                            $error = 'Image file is required.';
                        } else {
                            $sql = "INSERT INTO gallery_images (title, description, category, display_order, 
                                    is_active, image_path) 
                                    VALUES (:title, :description, :category, :display_order, :is_active, :image_path)";
                            executeQuery($pdo, $sql, [
                                'title' => $title,
                                'description' => $description,
                                'category' => $category,
                                'display_order' => $displayOrder,
                                'is_active' => $isActive,
                                'image_path' => $imagePath
                            ]);
                            $success = 'Image added successfully!';
                        }
                    }
                    
                    // Log activity
                    $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, 'manage_gallery', :desc)";
                    executeQuery($pdo, $logSql, [
                        'admin_id' => $_SESSION['admin_id'],
                        'desc' => ($imageIdToSave ? 'Updated' : 'Added') . " gallery image: $title"
                    ]);
                    
                } catch (Exception $e) {
                    $error = 'Failed to save image: ' . $e->getMessage();
                }
            }
        }
    }
    
    if ($formAction === 'delete_image') {
        $imageIdToDelete = $_POST['image_id'] ?? null;
        if ($imageIdToDelete) {
            try {
                // Get image path before deletion
                $image = fetchOne($pdo, "SELECT image_path FROM gallery_images WHERE id = :id", ['id' => $imageIdToDelete]);
                
                // Delete from database
                executeQuery($pdo, "DELETE FROM gallery_images WHERE id = :id", ['id' => $imageIdToDelete]);
                
                // Delete physical file
                if ($image && file_exists('../' . $image['image_path'])) {
                    unlink('../' . $image['image_path']);
                }
                
                $success = 'Image deleted successfully!';
                
                // Log activity
                $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, 'delete_gallery_image', :desc)";
                executeQuery($pdo, $logSql, [
                    'admin_id' => $_SESSION['admin_id'],
                    'desc' => "Deleted gallery image ID: $imageIdToDelete"
                ]);
                
            } catch (Exception $e) {
                $error = 'Failed to delete image.';
            }
        }
    }
}

// Fetch images with optional category filter
$categoryFilter = $_GET['category'] ?? 'all';
$sql = "SELECT * FROM gallery_images";
if ($categoryFilter !== 'all') {
    $sql .= " WHERE category = :category";
}
$sql .= " ORDER BY display_order ASC, created_at DESC";

$images = ($categoryFilter !== 'all') 
    ? fetchAll($pdo, $sql, ['category' => $categoryFilter])
    : fetchAll($pdo, $sql);

// Fetch single image for edit
$image = null;
if ($action === 'edit' && $imageId) {
    $image = fetchOne($pdo, "SELECT * FROM gallery_images WHERE id = :id", ['id' => $imageId]);
}

// Count images by category
$categoryCounts = [
    'all' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images")['count'],
    'office' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images WHERE category = 'office'")['count'],
    'field_work' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images WHERE category = 'field_work'")['count'],
    'events' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images WHERE category = 'events'")['count'],
    'community' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images WHERE category = 'community'")['count'],
    'general' => fetchOne($pdo, "SELECT COUNT(*) as count FROM gallery_images WHERE category = 'general'")['count']
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Management | SHU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php"><i class="bi bi-heart-fill"></i> SHU Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php" target="_blank"><i class="bi bi-globe"></i> View Website</a></li>
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
            <li class="nav-item"><a class="nav-link" href="slider.php"><i class="bi bi-images"></i> Hero Slider</a></li>
            <li class="nav-item"><a class="nav-link" href="team.php"><i class="bi bi-people"></i> Team Members</a></li>
            <li class="nav-item"><a class="nav-link active" href="gallery.php"><i class="bi bi-image"></i> Gallery</a></li>
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
                    <i class="bi bi-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i><?= htmlspecialchars($error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($action === 'list'): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2><i class="bi bi-image me-2"></i>Gallery Management</h2>
                        <p class="text-muted">Manage photos for About page, events, and field work</p>
                    </div>
                    <a href="gallery.php?action=create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add New Image
                    </a>
                </div>

                <!-- Category Filter Tabs -->
                <ul class="nav nav-pills mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'all' ? 'active' : '' ?>" href="gallery.php?category=all">
                            All Images <span class="badge bg-secondary ms-1"><?= $categoryCounts['all'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'office' ? 'active' : '' ?>" href="gallery.php?category=office">
                            Office <span class="badge bg-secondary ms-1"><?= $categoryCounts['office'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'field_work' ? 'active' : '' ?>" href="gallery.php?category=field_work">
                            Field Work <span class="badge bg-secondary ms-1"><?= $categoryCounts['field_work'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'events' ? 'active' : '' ?>" href="gallery.php?category=events">
                            Events <span class="badge bg-secondary ms-1"><?= $categoryCounts['events'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'community' ? 'active' : '' ?>" href="gallery.php?category=community">
                            Community <span class="badge bg-secondary ms-1"><?= $categoryCounts['community'] ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $categoryFilter === 'general' ? 'active' : '' ?>" href="gallery.php?category=general">
                            General <span class="badge bg-secondary ms-1"><?= $categoryCounts['general'] ?></span>
                        </a>
                    </li>
                </ul>

                <!-- Gallery Grid -->
                <?php if (!empty($images)): ?>
                    <div class="row g-4">
                        <?php foreach ($images as $img): ?>
                            <div class="col-md-6 col-lg-4 col-xl-3">
                                <div class="card h-100">
                                    <?php if ($img['image_path'] && file_exists('../' . $img['image_path'])): ?>
                                        <img src="../<?= htmlspecialchars($img['image_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($img['title']) ?>">
                                    <?php else: ?>
                                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="bi bi-image text-white" style="font-size: 3rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0"><?= htmlspecialchars($img['title']) ?></h6>
                                            <span class="badge bg-<?= $img['is_active'] ? 'success' : 'secondary' ?>">
                                                <?= $img['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">
                                            <?= htmlspecialchars(substr($img['description'] ?? '', 0, 60)) ?>...
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-info"><?= ucfirst(str_replace('_', ' ', $img['category'])) ?></span>
                                            <small class="text-muted">Order: <?= $img['display_order'] ?></small>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top">
                                        <a href="gallery.php?action=edit&id=<?= $img['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteImage(<?= $img['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-images text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No images in this category</h4>
                        <a href="gallery.php?action=create" class="btn btn-primary mt-3">
                            <i class="bi bi-plus-circle me-2"></i>Add Your First Image
                        </a>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- Create/Edit Form -->
                <div class="mb-4">
                    <a href="gallery.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Gallery
                    </a>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><?= $action === 'create' ? 'Add New Image' : 'Edit Image' ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_action" value="save_image">
                            <?php if ($image): ?>
                                <input type="hidden" name="image_id" value="<?= $image['id'] ?>">
                                <input type="hidden" name="existing_image" value="<?= $image['image_path'] ?>">
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" class="form-control" name="title" 
                                               value="<?= htmlspecialchars($image['title'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($image['description'] ?? '') ?></textarea>
                                        <small class="text-muted">Brief description of the image</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Image * (1200x800px recommended)</label>
                                        <?php if (isset($image['image_path']) && $image['image_path']): ?>
                                            <div class="mb-2">
                                                <img src="../<?= htmlspecialchars($image['image_path']) ?>" 
                                                     class="img-thumbnail" style="max-width: 100%;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="gallery_image" 
                                               accept="image/*" <?= !$image ? 'required' : '' ?>>
                                        <small class="text-muted">JPG/PNG, Max 2MB</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Category *</label>
                                        <select class="form-select" name="category" required>
                                            <option value="office" <?= ($image['category'] ?? '') == 'office' ? 'selected' : '' ?>>Office</option>
                                            <option value="field_work" <?= ($image['category'] ?? '') == 'field_work' ? 'selected' : '' ?>>Field Work</option>
                                            <option value="events" <?= ($image['category'] ?? '') == 'events' ? 'selected' : '' ?>>Events</option>
                                            <option value="community" <?= ($image['category'] ?? '') == 'community' ? 'selected' : '' ?>>Community</option>
                                            <option value="general" <?= ($image['category'] ?? 'general') == 'general' ? 'selected' : '' ?>>General</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Display Order</label>
                                        <input type="number" class="form-control" name="display_order" 
                                               value="<?= $image['display_order'] ?? 0 ?>" min="0">
                                        <small class="text-muted">Lower numbers appear first</small>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" name="is_active" 
                                               <?= ($image['is_active'] ?? true) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Active (Show on website)</label>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>Save Image
                                        </button>
                                        <a href="gallery.php" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display:none;">
        <input type="hidden" name="form_action" value="delete_image">
        <input type="hidden" name="image_id" id="deleteImageId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteImage(id) {
            if (confirm('Delete this image? The file will be permanently removed.')) {
                document.getElementById('deleteImageId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>