<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db.php';

// Fetch Impact Data - Match your SQL Dump column names
try {
    // We use the 'impact_stats' table from your dump
    $stats = fetchAll($pdo, "SELECT * FROM impact_stats WHERE is_active = 1 ORDER BY display_order ASC");
    
    // We use the 'posts' table, category 'success_story' from your dump
    $stories = fetchAll($pdo, "SELECT * FROM posts WHERE category = 'success_story' AND status = 'published' ORDER BY published_at DESC");
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

$pageTitle = "Our Impact | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigation (Copied from your About page for consistency) -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-heart-fill text-primary"></i>
                <span class="text-primary">Serving Hearts</span>-Uganda
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="programs.php">Programs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="impact.php">Impact</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary btn-sm pulse-button" href="get-involved.php#donate">Donate Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header (Starts after 76px because of fixed navbar) -->
    <section class="page-header">
        <div class="page-header-overlay"></div>
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">Our Impact</h1>
            <p class="lead" data-aos="fade-up" data-aos-delay="100">Measurable change and heart-warming stories from the field.</p>
        </div>
    </section>

    <!-- Impact Numbers Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4 text-center">
                <?php if (!empty($stats)): ?>
                    <?php foreach ($stats as $stat): ?>
                        <div class="col-md-3" data-aos="zoom-in">
                            <div class="stat-card p-4 border rounded shadow-sm">
                                <h2 class="text-primary fw-bold mb-1"><?= htmlspecialchars($stat['metric_value']) ?></h2>
                                <p class="text-dark fw-bold mb-0"><?= htmlspecialchars($stat['metric_name']) ?></p>
                                <p class="text-muted small mb-0"><?= htmlspecialchars($stat['metric_description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted">Impact statistics are being updated...</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Stories of Transformation</h2>
                <p class="lead text-muted">Direct evidence of community resilience and growth.</p>
            </div>
            
            <div class="row g-4">
                <?php if (!empty($stories)): ?>
                    <?php foreach ($stories as $story): ?>
                        <div class="col-md-4" data-aos="fade-up">
                            <div class="card news-card h-100 border-0 shadow-sm">
                                <img src="<?= htmlspecialchars($story['featured_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($story['title']) ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <span class="badge bg-success mb-2">Success Story</span>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($story['title']) ?></h5>
                                    <p class="card-text text-muted small"><?= htmlspecialchars($story['excerpt']) ?></p>
                                    <a href="news-single.php?slug=<?= $story['slug'] ?>" class="text-primary fw-bold text-decoration-none">Read Full Story &rarr;</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="bi bi- journal-text display-1 text-muted opacity-25"></i>
                        <p class="text-muted mt-3">We are currently documenting our latest success stories. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Serving Hearts-Uganda Limited. All rights reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>