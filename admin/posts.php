<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

$success = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$postId = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formAction = $_POST['form_action'] ?? '';
    
    // Create or Update Post
    if ($formAction === 'save_post') {
        $title = sanitize($_POST['title'] ?? '');
        $slug = sanitize($_POST['slug'] ?? '');
        $excerpt = sanitize($_POST['excerpt'] ?? '');
        $content = $_POST['content'] ?? ''; // Don't sanitize - keep HTML
        $category = sanitize($_POST['category'] ?? 'news');
        $status = sanitize($_POST['status'] ?? 'draft');
        $postIdToSave = $_POST['post_id'] ?? null;
        
        if (empty($title) || empty($content)) {
            $error = 'Title and content are required.';
        } else {
            // Generate slug if empty
            if (empty($slug)) {
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
            }
            
            // Handle image upload
            $featuredImage = '';
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                $fileType = $_FILES['featured_image']['type'];
                
                if (in_array($fileType, $allowedTypes)) {
                    $uploadDir = '../uploads/posts/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileName = time() . '_' . basename($_FILES['featured_image']['name']);
                    $uploadPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                        $featuredImage = 'uploads/posts/' . $fileName;
                    }
                }
            }
            
            try {
                if ($postIdToSave) {
                    // Update existing post
                    $sql = "UPDATE posts SET title = :title, slug = :slug, excerpt = :excerpt, 
                            content = :content, category = :category, status = :status";
                    $params = [
                        'title' => $title,
                        'slug' => $slug,
                        'excerpt' => $excerpt,
                        'content' => $content,
                        'category' => $category,
                        'status' => $status,
                        'id' => $postIdToSave
                    ];
                    
                    if ($featuredImage) {
                        $sql .= ", featured_image = :featured_image";
                        $params['featured_image'] = $featuredImage;
                    }
                    
                    if ($status === 'published') {
                        $sql .= ", published_at = NOW()";
                    }
                    
                    $sql .= " WHERE id = :id";
                    executeQuery($pdo, $sql, $params);
                    
                    $success = 'Post updated successfully!';
                } else {
                    // Create new post
                    $sql = "INSERT INTO posts (title, slug, excerpt, content, category, status, author_id, featured_image, published_at) 
                            VALUES (:title, :slug, :excerpt, :content, :category, :status, :author_id, :featured_image, " . 
                            ($status === 'published' ? 'NOW()' : 'NULL') . ")";
                    executeQuery($pdo, $sql, [
                        'title' => $title,
                        'slug' => $slug,
                        'excerpt' => $excerpt,
                        'content' => $content,
                        'category' => $category,
                        'status' => $status,
                        'author_id' => $_SESSION['admin_id'],
                        'featured_image' => $featuredImage
                    ]);
                    
                    $success = 'Post created successfully!';
                }
                
                // Log activity
                $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, :action, :desc)";
                executeQuery($pdo, $logSql, [
                    'admin_id' => $_SESSION['admin_id'],
                    'action' => $postIdToSave ? 'update_post' : 'create_post',
                    'desc' => ($postIdToSave ? 'Updated' : 'Created') . " post: $title"
                ]);
                
            } catch (Exception $e) {
                $error = 'Failed to save post: ' . $e->getMessage();
            }
        }
    }
    
    // Delete Post
    if ($formAction === 'delete_post') {
        $postIdToDelete = $_POST['post_id'] ?? null;
        if ($postIdToDelete) {
            try {
                executeQuery($pdo, "DELETE FROM posts WHERE id = :id", ['id' => $postIdToDelete]);
                $success = 'Post deleted successfully!';
                
                // Log activity
                $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, 'delete_post', :desc)";
                executeQuery($pdo, $logSql, [
                    'admin_id' => $_SESSION['admin_id'],
                    'desc' => "Deleted post ID: $postIdToDelete"
                ]);
            } catch (Exception $e) {
                $error = 'Failed to delete post.';
            }
        }
    }
}

// Fetch posts for list view
$posts = [];
if ($action === 'list') {
    $posts = fetchAll($pdo, "SELECT * FROM posts ORDER BY created_at DESC");
}

// Fetch single post for edit
$post = null;
if ($action === 'edit' && $postId) {
    $post = fetchOne($pdo, "SELECT * FROM posts WHERE id = :id", ['id' => $postId]);
}

$pageTitle = $action === 'create' ? 'Create Post' : ($action === 'edit' ? 'Edit Post' : 'Manage Posts');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | SHU Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">
                <i class="bi bi-heart-fill"></i> SHU Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank">
                            <i class="bi bi-globe"></i> View Website
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['admin_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">Navigation</h5>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="posts.php"><i class="bi bi-newspaper"></i> Blog Posts</a></li>
            <li class="nav-item"><a class="nav-link" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
            <li class="nav-item"><a class="nav-link" href="volunteers.php"><i class="bi bi-people"></i> Volunteers</a></li>
            <li class="nav-item"><a class="nav-link" href="messages.php"><i class="bi bi-envelope"></i> Messages</a></li>
            <li class="nav-item"><a class="nav-link" href="impact-stats.php"><i class="bi bi-bar-chart"></i> Impact Stats</a></li>
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
                <!-- List View -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="bi bi-newspaper me-2"></i>Blog Posts</h2>
                    <a href="posts.php?action=create" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Create New Post
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Published</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $p): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($p['title']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars(substr($p['excerpt'], 0, 60)) ?>...</small>
                                            </td>
                                            <td><span class="badge bg-primary"><?= ucfirst($p['category']) ?></span></td>
                                            <td>
                                                <span class="badge bg-<?= $p['status'] == 'published' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($p['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= $p['views'] ?></td>
                                            <td><small><?= $p['published_at'] ? date('M d, Y', strtotime($p['published_at'])) : 'Not published' ?></small></td>
                                            <td>
                                                <a href="posts.php?action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deletePost(<?= $p['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Create/Edit Form -->
                <div class="mb-4">
                    <a href="posts.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Posts
                    </a>
                </div>

                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0"><?= $action === 'create' ? 'Create New Post' : 'Edit Post' ?></h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="form_action" value="save_post">
                            <?php if ($post): ?>
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Title *</label>
                                        <input type="text" class="form-control" name="title" 
                                               value="<?= htmlspecialchars($post['title'] ?? '') ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Slug (URL-friendly)</label>
                                        <input type="text" class="form-control" name="slug" 
                                               value="<?= htmlspecialchars($post['slug'] ?? '') ?>"
                                               placeholder="leave-blank-for-auto-generation">
                                        <small class="text-muted">Leave blank to auto-generate from title</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Excerpt</label>
                                        <textarea class="form-control" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Content *</label>
                                        <textarea class="form-control" name="content" rows="15" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                                        <small class="text-muted">You can use HTML tags for formatting</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select class="form-select" name="category">
                                            <option value="programs" <?= ($post['category'] ?? '') == 'programs' ? 'selected' : '' ?>>Programs</option>
                                            <option value="success_story" <?= ($post['category'] ?? '') == 'success_story' ? 'selected' : '' ?>>Success Story</option>
                                            <option value="events" <?= ($post['category'] ?? '') == 'events' ? 'selected' : '' ?>>Events</option>
                                            <option value="news" <?= ($post['category'] ?? 'news') == 'news' ? 'selected' : '' ?>>News</option>
                                            <option value="announcement" <?= ($post['category'] ?? '') == 'announcement' ? 'selected' : '' ?>>Announcement</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="draft" <?= ($post['status'] ?? 'draft') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                            <option value="published" <?= ($post['status'] ?? '') == 'published' ? 'selected' : '' ?>>Published</option>
                                            <option value="archived" <?= ($post['status'] ?? '') == 'archived' ? 'selected' : '' ?>>Archived</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Featured Image</label>
                                        <?php if (isset($post['featured_image']) && $post['featured_image']): ?>
                                            <div class="mb-2">
                                                <img src="../<?= htmlspecialchars($post['featured_image']) ?>" 
                                                     class="img-thumbnail" style="max-width: 100%;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" name="featured_image" accept="image/*">
                                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-2"></i>Save Post
                                        </button>
                                        <a href="posts.php" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="form_action" value="delete_post">
        <input type="hidden" name="post_id" id="deletePostId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deletePost(id) {
            if (confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
                document.getElementById('deletePostId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>