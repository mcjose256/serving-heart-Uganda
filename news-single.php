<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
$post = fetchOne($pdo, "SELECT * FROM posts WHERE slug = :slug AND status = 'published'", ['slug' => $slug]);

if (!$post) {
    header("Location: news.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> | SHU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Serving Hearts-Uganda</a>
            <a href="news.php" class="btn btn-outline-secondary btn-sm">Back to News</a>
        </div>
    </nav>

    <section class="py-5 mt-5">
        <div class="container pt-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="img-fluid rounded mb-4 shadow w-100" style="max-height: 450px; object-fit: cover;">
                    <span class="badge bg-primary mb-2"><?= ucfirst($post['category']) ?></span>
                    <h1 class="fw-bold mb-3"><?= htmlspecialchars($post['title']) ?></h1>
                    <p class="text-muted small mb-4">Published: <?= date('M d, Y', strtotime($post['published_at'])) ?></p>
                    <hr>
                    <div class="news-content-area mt-4">
                        <?= $post['content'] ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 text-center">
        <p class="small mb-0">&copy; Serving Hearts-Uganda</p>
    </footer>
</body>
</html>