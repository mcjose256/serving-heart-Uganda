<?php
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once 'includes/db.php';

// Initialize variables
$latestPosts = [];
$impactStats = [];
$sliderImages = [];

// Fetch data from database
try {
    if (isset($pdo) && $pdo instanceof PDO) {
        // Fetch latest 3 published posts
        $latestPosts = fetchAll($pdo, "SELECT * FROM posts WHERE status = 'published' ORDER BY published_at DESC LIMIT 3");
        
        // Fetch impact statistics
        $impactStats = fetchAll($pdo, "SELECT * FROM impact_stats WHERE is_active = TRUE ORDER BY display_order ASC LIMIT 4");
        
        // Fetch active slider images
        $sliderImages = fetchAll($pdo, "SELECT * FROM slider_images WHERE is_active = TRUE ORDER BY display_order ASC LIMIT 5");
    }
} catch (Exception $e) {
    // Log error but don't break the page
    error_log("Database query error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Serving Hearts-Uganda Limited empowers communities through menstrual hygiene management, income generating activities, WASH, and HIV/AIDS prevention programs.">
    <meta name="keywords" content="NGO Uganda, menstrual hygiene, WASH, community development, donate Uganda">
    <title>Serving Hearts-Uganda Limited | Empowering Communities, Transforming Lives</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="programs.php">Programs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="impact.php">Impact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="news.php">News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary btn-sm pulse-button" href="get-involved.php#donate">
                            <i class="bi bi-heart"></i> Donate Now
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-primary btn-sm" href="get-involved.php#volunteer">
                            Volunteer
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Carousel Section - DYNAMIC -->
    <section class="hero-carousel-section">
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <!-- Indicators -->
            <?php if (!empty($sliderImages)): ?>
                <div class="carousel-indicators">
                    <?php foreach ($sliderImages as $index => $slide): ?>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" 
                                class="<?= $index === 0 ? 'active' : '' ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Slides -->
            <div class="carousel-inner">
                <?php if (!empty($sliderImages)): ?>
                    <?php foreach ($sliderImages as $index => $slide): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= htmlspecialchars($slide['image_path']) ?>');">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 text-center">
                                            <h1 class="display-3 fw-bold text-white mb-4 animate-up">
                                                <?= htmlspecialchars($slide['title']) ?>
                                            </h1>
                                            <?php if (!empty($slide['description'])): ?>
                                                <p class="lead text-white mb-4 animate-up-delay-1">
                                                    <?= htmlspecialchars($slide['description']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <div class="hero-buttons animate-up-delay-2">
                                                <?php if (!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
                                                    <a href="<?= htmlspecialchars($slide['button_link']) ?>" class="btn btn-primary btn-lg me-3">
                                                        <i class="bi bi-arrow-right-circle"></i> <?= htmlspecialchars($slide['button_text']) ?>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="about.php" class="btn btn-outline-light btn-lg">
                                                    <i class="bi bi-info-circle"></i> Learn More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback slide if no slides in database -->
                    <div class="carousel-item active">
                        <div class="hero-slide" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('assets/images/slider/slide-1.jpg');">
                            <div class="container hero-content">
                                <div class="row justify-content-center text-center">
                                    <div class="col-lg-8">
                                        <h1 class="display-3 fw-bold text-white mb-4">
                                            Empowering Communities, Transforming Lives
                                        </h1>
                                        <p class="lead text-white mb-4">
                                            Supporting girls, women, and communities across Uganda through sustainable development programs
                                        </p>
                                        <div class="hero-buttons">
                                            <a href="get-involved.php#donate" class="btn btn-primary btn-lg me-3">
                                                <i class="bi bi-heart-fill"></i> Make a Donation
                                            </a>
                                            <a href="about.php" class="btn btn-outline-light btn-lg">
                                                <i class="bi bi-info-circle"></i> Learn More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Controls -->
            <?php if (count($sliderImages) > 1): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
    </section>

    <!-- Impact Stats with Animation - DYNAMIC -->
    <section class="impact-stats py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <?php if (!empty($impactStats)): ?>
                    <?php foreach ($impactStats as $index => $stat): ?>
                        <div class="col-md-3 col-sm-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                            <div class="stat-box">
                                <?php 
                                $icons = [
                                    'Girls Reached' => 'bi-people-fill',
                                    'Families Supported' => 'bi-shop',
                                    'Communities Served' => 'bi-droplet-fill',
                                    'Years of Service' => 'bi-calendar-check'
                                ];
                                $icon = $icons[$stat['metric_name']] ?? 'bi-star-fill';
                                ?>
                                <i class="bi <?= $icon ?> text-primary display-4 mb-3"></i>
                                <h3 class="fw-bold text-primary mb-0 counter" data-target="<?= preg_replace('/[^0-9]/', '', $stat['metric_value']) ?>"><?= htmlspecialchars($stat['metric_value']) ?></h3>
                                <p class="text-muted"><?= htmlspecialchars($stat['metric_description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Fallback stats if none in database -->
                    <div class="col-md-3 col-sm-6 mb-4 mb-md-0" data-aos="fade-up">
                        <div class="stat-box">
                            <i class="bi bi-people-fill text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold text-primary mb-0">5,000+</h3>
                            <p class="text-muted">Girls Reached</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-box">
                            <i class="bi bi-shop text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold text-primary mb-0">300+</h3>
                            <p class="text-muted">Families Supported</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-box">
                            <i class="bi bi-droplet-fill text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold text-primary mb-0">20+</h3>
                            <p class="text-muted">Communities Served</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-box">
                            <i class="bi bi-calendar-check text-primary display-4 mb-3"></i>
                            <h3 class="fw-bold text-primary mb-0">10</h3>
                            <p class="text-muted">Years of Service</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Who We Are -->
    <section class="about-intro py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="image-stack">
                        <img src="assets/images/about-1.jpg" alt="Serving Hearts Uganda Team" class="img-fluid rounded shadow main-image">
                        <div class="floating-badge">
                            <i class="bi bi-award-fill text-warning"></i>
                            <span>10 Years</span>
                            <small>Serving Communities</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="fw-bold mb-4">Who We Are</h2>
                    <p class="lead text-muted mb-3">
                        Serving Hearts-Uganda Limited (SHU) is a registered non-profit organization 
                        dedicated to improving the lives of vulnerable communities across Uganda.
                    </p>
                    <p class="mb-3">
                        Founded in 2015 and officially registered in 2017, we work tirelessly to address 
                        critical needs in menstrual hygiene management, sustainable income generation, 
                        water and sanitation, and health education.
                    </p>
                    <p class="mb-4">
                        Based in Wakiso District, Kasangati Town Council, our grassroots approach ensures 
                        that support reaches those who need it most, creating lasting change through dignity, 
                        empowerment, and community partnership.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="about.php" class="btn btn-primary">
                            Learn More About Us <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="impact.php" class="btn btn-outline-primary">
                            View Our Impact
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Programs -->
    <section class="programs py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Our Focus Areas</h2>
                <p class="lead text-muted">Four pillars of community transformation</p>
            </div>
            <div class="row g-4">
                <!-- Program 1 -->
                <div class="col-md-6 col-lg-3" data-aos="flip-left" data-aos-delay="0">
                    <div class="card program-card h-100 border-0 shadow-sm">
                        <div class="program-card-header bg-gradient-primary"></div>
                        <div class="card-body text-center">
                            <div class="program-icon mb-3">
                                <i class="bi bi-gender-female display-3 text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-3">Menstrual Hygiene Management</h5>
                            <p class="card-text text-muted">
                                Providing dignity and education to girls and women through hygiene kits, 
                                workshops, and breaking period poverty barriers.
                            </p>
                            <a href="programs.php#mhm" class="btn btn-sm btn-outline-primary mt-2">
                                Learn More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Program 2 -->
                <div class="col-md-6 col-lg-3" data-aos="flip-left" data-aos-delay="100">
                    <div class="card program-card h-100 border-0 shadow-sm">
                        <div class="program-card-header bg-gradient-success"></div>
                        <div class="card-body text-center">
                            <div class="program-icon mb-3">
                                <i class="bi bi-graph-up-arrow display-3 text-success"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-3">Income Generating Activities</h5>
                            <p class="card-text text-muted">
                                Empowering families with skills training, startup kits, and mentorship 
                                for sustainable livelihoods and food security.
                            </p>
                            <a href="programs.php#iga" class="btn btn-sm btn-outline-success mt-2">
                                Learn More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Program 3 -->
                <div class="col-md-6 col-lg-3" data-aos="flip-left" data-aos-delay="200">
                    <div class="card program-card h-100 border-0 shadow-sm">
                        <div class="program-card-header bg-gradient-info"></div>
                        <div class="card-body text-center">
                            <div class="program-icon mb-3">
                                <i class="bi bi-droplet-half display-3 text-info"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-3">Water, Sanitation & Hygiene</h5>
                            <p class="card-text text-muted">
                                Improving community health through clean water access, sanitation 
                                facilities, and hygiene behavior change programs.
                            </p>
                            <a href="programs.php#wash" class="btn btn-sm btn-outline-info mt-2">
                                Learn More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Program 4 -->
                <div class="col-md-6 col-lg-3" data-aos="flip-left" data-aos-delay="300">
                    <div class="card program-card h-100 border-0 shadow-sm">
                        <div class="program-card-header bg-gradient-warning"></div>
                        <div class="card-body text-center">
                            <div class="program-icon mb-3">
                                <i class="bi bi-shield-fill-check display-3 text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold mb-3">HIV/AIDS Prevention</h5>
                            <p class="card-text text-muted">
                                Raising awareness, providing testing support, and educating communities 
                                on HIV/AIDS and infection prevention.
                            </p>
                            <a href="programs.php#hiv" class="btn btn-sm btn-outline-warning mt-2">
                                Learn More <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest News (Dynamic from Database) -->
    <section class="latest-news py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Latest News & Updates</h2>
                <p class="lead text-muted">Stay informed about our recent activities and impact</p>
            </div>
            <div class="row g-4">
                <?php if (isset($latestPosts) && is_array($latestPosts) && count($latestPosts) > 0): ?>
                    <?php foreach ($latestPosts as $index => $post): ?>
                        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="<?= $index * 100 ?>">
                            <div class="card news-card h-100 border-0 shadow-sm">
                                <?php if (isset($post['featured_image']) && !empty($post['featured_image'])): ?>
                                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">
                                <?php else: ?>
                                    <div class="card-img-top placeholder-image">
                                        <i class="bi bi-image display-1 text-white"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <?php 
                                        $badgeClass = 'primary';
                                        if ($post['category'] == 'success_story') $badgeClass = 'success';
                                        elseif ($post['category'] == 'events') $badgeClass = 'info';
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= ucfirst(str_replace('_', ' ', htmlspecialchars($post['category']))) ?>
                                        </span>
                                        <small class="text-muted ms-2">
                                            <i class="bi bi-calendar3"></i> <?= date('M d, Y', strtotime($post['published_at'])) ?>
                                        </small>
                                    </div>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($post['title']) ?></h5>
                                    <p class="card-text text-muted">
                                        <?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>...
                                    </p>
                                    <a href="news-single.php?slug=<?= htmlspecialchars($post['slug']) ?>" class="btn btn-sm btn-link p-0">
                                        Read More <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            No news articles available at the moment. Check back soon for updates!
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="news.php" class="btn btn-outline-primary">
                    View All News <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section py-5 bg-gradient-primary text-white" data-aos="fade-up">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Your Support Changes Lives</h2>
            <p class="lead mb-4">
                Join us in empowering communities across Uganda. Every contribution makes a difference.
            </p>
            <div class="cta-buttons">
                <a href="get-involved.php#donate" class="btn btn-light btn-lg me-3">
                    <i class="bi bi-heart-fill"></i> Donate Now
                </a>
                <a href="get-involved.php#volunteer" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-people-fill"></i> Volunteer With Us
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-heart-fill text-primary"></i> Serving Hearts-Uganda
                    </h5>
                    <p class="text-muted small">
                        A registered non-profit organization (Company Limited by Guarantee) 
                        dedicated to empowering communities through sustainable development programs.
                    </p>
                    <p class="small">
                        <strong>URSB Reg No:</strong> 80020000220350<br>
                        <strong>Established:</strong> 2015
                    </p>
                </div>
                
                <div class="col-md-2">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="about.php" class="text-muted text-decoration-none">About Us</a></li>
                        <li class="mb-2"><a href="programs.php" class="text-muted text-decoration-none">Programs</a></li>
                        <li class="mb-2"><a href="impact.php" class="text-muted text-decoration-none">Impact</a></li>
                        <li class="mb-2"><a href="news.php" class="text-muted text-decoration-none">News</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Get Involved</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="get-involved.php#donate" class="text-muted text-decoration-none">Make a Donation</a></li>
                        <li class="mb-2"><a href="get-involved.php#volunteer" class="text-muted text-decoration-none">Volunteer</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Partner With Us</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2">
                            <i class="bi bi-geo-alt-fill"></i> 
                            Wakiso District, Kasangati Town Council
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-envelope-fill"></i> 
                            info@servinghearts.ug
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-phone-fill"></i> 
                            +256 XXX XXX XXX
                        </li>
                    </ul>
                    <div class="social-links mt-3">
                        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin fs-5"></i></a>
                    </div>
                </div>
            </div>
            
            <hr class="border-secondary my-4">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-muted mb-0">
                        &copy; <?= date('Y') ?> Serving Hearts-Uganda Limited. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-muted text-decoration-none small me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>