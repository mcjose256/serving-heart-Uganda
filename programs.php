<?php
session_start();
require_once 'includes/db.php';

$pageTitle = "Our Programs | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Learn about Serving Hearts-Uganda's community empowerment programs: Menstrual Hygiene Management, Income Generating Activities, WASH, and HIV/AIDS Prevention.">
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link active" href="programs.php">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="impact.php">Impact</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-primary btn-sm pulse-button" href="get-involved.php#donate">
                            <i class="bi bi-heart"></i> Donate Now
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-primary btn-sm" href="get-involved.php#volunteer">Volunteer</a>
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
                    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up">Our Programs</h1>
                    <p class="lead text-white" data-aos="fade-up" data-aos-delay="100">
                        Empowering communities through sustainable, impactful interventions
                    </p>
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white">Programs</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Overview -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center" data-aos="fade-up">
                    <h2 class="fw-bold mb-4">Four Pillars of Community Transformation</h2>
                    <p class="lead text-muted">
                        Our integrated approach addresses the root causes of poverty and inequality, 
                        creating sustainable solutions that empower communities to thrive.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Program 1: Menstrual Hygiene Management -->
    <section id="mhm" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="program-badge mb-3">
                        <i class="bi bi-gender-female"></i> Program 01
                    </div>
                    <h2 class="fw-bold mb-4">Menstrual Hygiene Management</h2>
                    <p class="lead text-primary mb-3">Breaking Period Poverty, Restoring Dignity</p>
                    
                    <h5 class="fw-bold mt-4 mb-3">The Challenge</h5>
                    <p class="text-muted">
                        Millions of girls in Uganda miss school during their periods due to lack of sanitary products, 
                        proper facilities, and education. This leads to reduced academic performance, early dropout, 
                        and perpetuates the cycle of poverty.
                    </p>

                    <h5 class="fw-bold mt-4 mb-3">Our Approach</h5>
                    <ul class="custom-list">
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Distribution of reusable sanitary pad kits</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Menstrual health education workshops</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Training teachers and parents as champions</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Building school WASH facilities</li>
                        <li><i class="bi bi-check-circle-fill text-primary me-2"></i> Breaking stigma through community dialogues</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-3">Impact</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-primary fw-bold">5,000+</h3>
                                <p class="small mb-0">Girls Reached</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-primary fw-bold">30%</h3>
                                <p class="small mb-0">Reduced Absenteeism</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="assets/images/programs/mhm.jpg" alt="Menstrual Hygiene Management" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Program 2: Income Generating Activities -->
    <section id="iga" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <div class="program-badge mb-3">
                        <i class="bi bi-graph-up-arrow"></i> Program 02
                    </div>
                    <h2 class="fw-bold mb-4">Income Generating Activities (IGAs)</h2>
                    <p class="lead text-success mb-3">Building Sustainable Livelihoods</p>
                    
                    <h5 class="fw-bold mt-4 mb-3">The Challenge</h5>
                    <p class="text-muted">
                        Many families struggle with food insecurity and lack access to sustainable income sources. 
                        Without economic empowerment, communities remain trapped in cycles of poverty and dependency.
                    </p>

                    <h5 class="fw-bold mt-4 mb-3">Our Approach</h5>
                    <ul class="custom-list">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Skills training in agriculture and entrepreneurship</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Provision of startup kits (seeds, tools, livestock)</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Formation of savings and credit groups</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Business mentorship and coaching</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Market linkage and value chain development</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-3">Key Activities</h5>
                    <div class="row g-2">
                        <div class="col-6"><span class="badge bg-success mb-2">Vegetable Farming</span></div>
                        <div class="col-6"><span class="badge bg-success mb-2">Poultry Keeping</span></div>
                        <div class="col-6"><span class="badge bg-success mb-2">Tailoring & Crafts</span></div>
                        <div class="col-6"><span class="badge bg-success mb-2">Small Trading</span></div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Impact</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-success fw-bold">300+</h3>
                                <p class="small mb-0">Families Empowered</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-success fw-bold">150K</h3>
                                <p class="small mb-0">Avg. Monthly Income (UGX)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <img src="assets/images/programs/iga.jpg" alt="Income Generating Activities" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Program 3: WASH -->
    <section id="wash" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="program-badge mb-3">
                        <i class="bi bi-droplet-half"></i> Program 03
                    </div>
                    <h2 class="fw-bold mb-4">Water, Sanitation & Hygiene (WASH)</h2>
                    <p class="lead text-info mb-3">Clean Water, Healthy Communities</p>
                    
                    <h5 class="fw-bold mt-4 mb-3">The Challenge</h5>
                    <p class="text-muted">
                        Lack of access to clean water and proper sanitation facilities leads to waterborne diseases, 
                        affecting health, education, and economic productivity in rural communities.
                    </p>

                    <h5 class="fw-bold mt-4 mb-3">Our Approach</h5>
                    <ul class="custom-list">
                        <li><i class="bi bi-check-circle-fill text-info me-2"></i> Construction of water sources (boreholes, wells)</li>
                        <li><i class="bi bi-check-circle-fill text-info me-2"></i> Building sanitation facilities (latrines, handwashing stations)</li>
                        <li><i class="bi bi-check-circle-fill text-info me-2"></i> Hygiene behavior change campaigns</li>
                        <li><i class="bi bi-check-circle-fill text-info me-2"></i> Water treatment and safe storage training</li>
                        <li><i class="bi bi-check-circle-fill text-info me-2"></i> Formation of WASH management committees</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-3">Focus Areas</h5>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="feature-box">
                                <i class="bi bi-droplet text-info fs-3"></i>
                                <h6 class="fw-bold mt-2 mb-1">Water Access</h6>
                                <small class="text-muted">Clean, safe drinking water</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-box">
                                <i class="bi bi-house-door text-info fs-3"></i>
                                <h6 class="fw-bold mt-2 mb-1">Sanitation</h6>
                                <small class="text-muted">Proper toilet facilities</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-box">
                                <i class="bi bi-hand-index text-info fs-3"></i>
                                <h6 class="fw-bold mt-2 mb-1">Hygiene</h6>
                                <small class="text-muted">Handwashing practices</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="feature-box">
                                <i class="bi bi-people text-info fs-3"></i>
                                <h6 class="fw-bold mt-2 mb-1">Community</h6>
                                <small class="text-muted">Behavior change campaigns</small>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3">Impact</h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-info fw-bold">20+</h3>
                                <p class="small mb-0">Communities Served</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="impact-mini-card">
                                <h3 class="text-info fw-bold">40%</h3>
                                <p class="small mb-0">Disease Reduction</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <img src="assets/images/programs/wash.jpg" alt="WASH Program" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Program 4: HIV/AIDS Prevention -->
    <section id="hiv" class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                    <div class="program-badge mb-3">
                        <i class="bi bi-shield-fill-check"></i> Program 04
                    </div>
                    <h2 class="fw-bold mb-4">HIV/AIDS & Infection Prevention</h2>
                    <p class="lead text-warning mb-3">Education, Testing, Support</p>
                    
                    <h5 class="fw-bold mt-4 mb-3">The Challenge</h5>
                    <p class="text-muted">
                        Despite progress, HIV/AIDS remains a significant health challenge in Uganda. 
                        Stigma, limited testing access, and lack of comprehensive sex education contribute to new infections.
                    </p>

                    <h5 class="fw-bold mt-4 mb-3">Our Approach</h5>
                    <ul class="custom-list">
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Community awareness and education campaigns</li>
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Voluntary counseling and testing (VCT) support</li>
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Youth-focused prevention programs</li>
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Stigma reduction initiatives</li>
                        <li><i class="bi bi-check-circle-fill text-warning me-2"></i> Referrals to treatment and care services</li>
                    </ul>

                    <h5 class="fw-bold mt-4 mb-3">Key Messages</h5>
                    <div class="alert alert-warning">
                        <ul class="mb-0">
                            <li>Know your status - get tested regularly</li>
                            <li>Prevention through education and safe practices</li>
                            <li>Treatment adherence saves lives</li>
                            <li>Zero stigma, full support</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <a href="contact.php" class="btn btn-warning">
                            <i class="bi bi-info-circle me-2"></i>Get More Information
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                    <img src="assets/images/programs/hiv.jpg" alt="HIV/AIDS Prevention" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- How We Work -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Our Implementation Approach</h2>
                <p class="lead text-muted">Community-centered, sustainable, measurable</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="process-card text-center">
                        <div class="process-number">01</div>
                        <h5 class="fw-bold mt-3">Assessment</h5>
                        <p class="text-muted small">Community needs analysis and baseline surveys</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="process-card text-center">
                        <div class="process-number">02</div>
                        <h5 class="fw-bold mt-3">Planning</h5>
                        <p class="text-muted small">Co-design solutions with community stakeholders</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="process-card text-center">
                        <div class="process-number">03</div>
                        <h5 class="fw-bold mt-3">Implementation</h5>
                        <p class="text-muted small">Deliver programs with local ownership</p>
                    </div>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="process-card text-center">
                        <div class="process-number">04</div>
                        <h5 class="fw-bold mt-3">Monitoring</h5>
                        <p class="text-muted small">Track impact and adapt for sustainability</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-gradient-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Support Our Programs</h2>
            <p class="lead mb-4">
                Your contribution enables us to expand these life-changing programs to more communities
            </p>
            <div class="cta-buttons">
                <a href="get-involved.php#donate" class="btn btn-light btn-lg me-3">
                    <i class="bi bi-heart-fill"></i> Donate to a Program
                </a>
                <a href="get-involved.php#volunteer" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-people-fill"></i> Volunteer
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
                        A registered non-profit organization dedicated to empowering communities through sustainable development programs.
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
                        <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Contact Us</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill"></i> Wakiso District, Kasangati Town Council</li>
                        <li class="mb-2"><i class="bi bi-envelope-fill"></i> info@servinghearts.ug</li>
                        <li class="mb-2"><i class="bi bi-phone-fill"></i> +256 XXX XXX XXX</li>
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
                    <p class="small text-muted mb-0">&copy; <?= date('Y') ?> Serving Hearts-Uganda Limited. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-muted text-decoration-none small me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>