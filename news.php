<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'includes/db.php';

try {
    // Fetch news, events, and announcements
    $news = fetchAll($pdo, "SELECT * FROM posts WHERE category IN ('news', 'events', 'announcement') AND status = 'published' ORDER BY published_at DESC");
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

$pageTitle = "News & Updates | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-heart-fill text-primary"></i>
                <span class="text-primary">Serving Hearts</span>-Uganda
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="programs.php">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="impact.php">Impact</a></li>
                    <li class="nav-item"><a class="nav-link active" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item ms-lg-3"><a class="btn btn-primary btn-sm" href="get-involved.php#donate">Donate Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-header">
        <div class="page-header-overlay"></div>
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">News & Updates</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Updates from our various field activities across Uganda.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <?php if (!empty($news)): ?>
                    <?php foreach ($news as $item): ?>
                        <div class="col-md-4" data-aos="fade-up">
                            <div class="card news-card h-100 border-0 shadow-sm">
                                <img src="<?= htmlspecialchars($item['featured_image']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2"><?= ucfirst($item['category']) ?></span>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($item['excerpt']) ?></p>
                                    <a href="news-single.php?slug=<?= $item['slug'] ?>" class="btn btn-outline-primary btn-sm mt-2">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">No news articles available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Serving Hearts-Uganda Limited. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>