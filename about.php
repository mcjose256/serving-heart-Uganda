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

// Fetch team members
$teamMembers = [];
$galleryImages = [];

try {
    if (isset($pdo) && $pdo instanceof PDO) {
        // Fetch active team members
        $teamMembers = fetchAll($pdo, "SELECT * FROM team_members WHERE is_active = TRUE ORDER BY display_order ASC");
        
        // Fetch gallery images (office category for About page)
        $galleryImages = fetchAll($pdo, "SELECT * FROM gallery_images WHERE category = 'office' AND is_active = TRUE ORDER BY display_order ASC LIMIT 6");
    }
} catch (Exception $e) {
    error_log("Database query error: " . $e->getMessage());
}

// Page title
$pageTitle = "About Us | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Serving Hearts-Uganda Limited, our mission, vision, and commitment to empowering communities across Uganda since 2015.">
    <meta name="keywords" content="About SHU, Serving Hearts Uganda, NGO mission, community development Uganda">
    <title><?= $pageTitle ?></title>
    
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php">About Us</a>
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

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-overlay"></div>
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up">About Us</h1>
                    <p class="lead text-white" data-aos="fade-up" data-aos-delay="100">
                        Empowering communities across Uganda since 2015
                    </p>
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <img src="assets/images/about-team.jpg" alt="SHU Team" class="img-fluid rounded shadow-lg">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="badge bg-primary mb-3">Who We Are</span>
                    <h2 class="fw-bold mb-4">Serving Hearts-Uganda Limited</h2>
                    <p class="lead text-muted mb-4">
                        A registered non-profit organization dedicated to transforming lives and empowering 
                        vulnerable communities across Uganda.
                    </p>
                    <p class="mb-3">
                        Serving Hearts-Uganda Limited (SHU) is a Company Limited by Guarantee, officially 
                        registered with the Uganda Registration Services Bureau (URSB) under registration 
                        number <strong>80020000220350</strong>.
                    </p>
                    <p class="mb-4">
                        Founded in <strong>2015</strong> and formally registered in <strong>2017</strong>, 
                        SHU was established with a clear vision: to create sustainable solutions to the 
                        challenges facing vulnerable communities, particularly girls, women, and families 
                        in rural and peri-urban areas of Uganda.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card">
                                <h3 class="text-primary fw-bold mb-0">2015</h3>
                                <p class="text-muted small mb-0">Founded</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card">
                                <h3 class="text-primary fw-bold mb-0">2017</h3>
                                <p class="text-muted small mb-0">Registered</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission, Vision, Values -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Our Foundation</h2>
                <p class="lead text-muted">Guided by purpose, driven by impact</p>
            </div>
            
            <div class="row g-4">
                <!-- Mission -->
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="0">
                    <div class="card h-100 border-0 shadow-sm mission-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-4">
                                <i class="bi bi-bullseye display-3 text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Our Mission</h4>
                            <p class="text-muted">
                                To empower vulnerable communities through sustainable programs in menstrual 
                                hygiene management, income generation, water and sanitation, and health education, 
                                creating lasting positive change and promoting dignity for all.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Vision -->
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="100">
                    <div class="card h-100 border-0 shadow-sm vision-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-4">
                                <i class="bi bi-eye display-3 text-success"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Our Vision</h4>
                            <p class="text-muted">
                                A Uganda where every girl, woman, and family has access to essential resources, 
                                opportunities for sustainable livelihoods, and the dignity to live healthy, 
                                empowered lives free from poverty and stigma.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Approach -->
                <div class="col-md-4" data-aos="flip-left" data-aos-delay="200">
                    <div class="card h-100 border-0 shadow-sm approach-card">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-4">
                                <i class="bi bi-gear display-3 text-info"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Our Approach</h4>
                            <p class="text-muted">
                                Community-centered, grassroots interventions that address root causes, build 
                                local capacity, and create sustainable solutions through partnership, education, 
                                and empowerment rather than dependency.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Our Core Values</h2>
                <p class="lead text-muted">The principles that guide everything we do</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="0">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Integrity</h5>
                        <p class="text-muted small">
                            Transparency, accountability, and ethical conduct in all our operations
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-heart text-danger"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Compassion</h5>
                        <p class="text-muted small">
                            Empathy and genuine care for the communities we serve
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-award text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Excellence</h5>
                        <p class="text-muted small">
                            Commitment to quality, innovation, and continuous improvement
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-people text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Community</h5>
                        <p class="text-muted small">
                            Collaboration, participation, and local ownership of solutions
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="400">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-lightning text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Empowerment</h5>
                        <p class="text-muted small">
                            Building capacity and enabling self-sufficiency
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="500">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-star text-info"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Dignity</h5>
                        <p class="text-muted small">
                            Respecting and upholding the worth of every individual
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="600">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-recycle text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Sustainability</h5>
                        <p class="text-muted small">
                            Creating lasting solutions that endure beyond our intervention
                        </p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="700">
                    <div class="value-card text-center">
                        <div class="value-icon">
                            <i class="bi bi-hand-thumbs-up text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Partnership</h5>
                        <p class="text-muted small">
                            Working together with stakeholders for greater impact
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Timeline -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Our Journey</h2>
                <p class="lead text-muted">From humble beginnings to impactful results</p>
            </div>
            
            <div class="timeline">
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2015</div>
                        <h4 class="fw-bold">The Beginning</h4>
                        <p class="text-muted">
                            Serving Hearts-Uganda was founded by a group of passionate individuals who 
                            witnessed firsthand the challenges facing girls and women in underserved communities. 
                            What started as small-scale menstrual hygiene awareness campaigns laid the 
                            foundation for comprehensive community development.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2017</div>
                        <h4 class="fw-bold">Official Registration</h4>
                        <p class="text-muted">
                            SHU became a legally registered Company Limited by Guarantee (URSB Reg No: 80020000220350). 
                            This milestone enabled us to expand operations, secure partnerships, and reach more 
                            communities with structured programs.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2018-2020</div>
                        <h4 class="fw-bold">Program Expansion</h4>
                        <p class="text-muted">
                            We expanded beyond menstrual hygiene to include Income Generating Activities (IGAs), 
                            Water, Sanitation and Hygiene (WASH) initiatives, and HIV/AIDS prevention. 
                            Our integrated approach addressed multiple community needs simultaneously.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2021-2023</div>
                        <h4 class="fw-bold">Growing Impact</h4>
                        <p class="text-muted">
                            Reached over 5,000 girls with menstrual hygiene support, empowered 300+ families 
                            with sustainable livelihoods, and improved WASH access in 20+ communities. 
                            Our evidence-based approach demonstrated measurable results.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2024-Present</div>
                        <h4 class="fw-bold">Scaling for Greater Impact</h4>
                        <p class="text-muted">
                            Continuing to deepen our impact in existing communities while expanding to new areas. 
                            Strengthening partnerships, enhancing program quality, and building capacity for 
                            long-term sustainability.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Registration & Legal Info -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-lg" data-aos="zoom-in">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <i class="bi bi-shield-fill-check text-primary display-1"></i>
                                <h3 class="fw-bold mt-3">Legal & Registration Information</h3>
                            </div>
                            
                            <div class="row g-4 text-center">
                                <div class="col-md-6">
                                    <div class="legal-info-box">
                                        <i class="bi bi-building text-primary fs-1 mb-3"></i>
                                        <h5 class="fw-bold">Legal Structure</h5>
                                        <p class="mb-0">Company Limited by Guarantee</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="legal-info-box">
                                        <i class="bi bi-file-earmark-text text-success fs-1 mb-3"></i>
                                        <h5 class="fw-bold">URSB Registration No.</h5>
                                        <p class="mb-0"><strong>80020000220350</strong></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="legal-info-box">
                                        <i class="bi bi-geo-alt text-info fs-1 mb-3"></i>
                                        <h5 class="fw-bold">Head Office</h5>
                                        <p class="mb-0">Wakiso District<br>Kasangati Town Council</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="legal-info-box">
                                        <i class="bi bi-calendar-check text-warning fs-1 mb-3"></i>
                                        <h5 class="fw-bold">Year Established</h5>
                                        <p class="mb-0">Founded: 2015<br>Registered: 2017</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-4 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Transparency Commitment:</strong> All our programs and finances 
                                are managed with complete transparency and accountability. Annual reports 
                                are available upon request.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-gradient-primary text-white" data-aos="fade-up">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Join Us in Making a Difference</h2>
            <p class="lead mb-4">
                Your support enables us to continue transforming lives across Uganda
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
    
    <script>
        // Image viewer function
        function viewImage(src, title) {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img src="${src}" class="img-fluid w-100" alt="${title}">
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            modal.addEventListener('hidden.bs.modal', () => modal.remove());
        }
    </script>
</body>
</html>