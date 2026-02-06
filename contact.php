<?php
session_start();
require_once 'includes/db.php';

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = sanitize($_POST['subject'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    // Validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!isValidEmail($email)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            // Insert into database
            $sql = "INSERT INTO contact_messages (name, email, phone, subject, message, status) 
                    VALUES (:name, :email, :phone, :subject, :message, 'unread')";
            executeQuery($pdo, $sql, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            ]);
            
            $success = 'Thank you for contacting us! We will get back to you within 24-48 hours.';
            
            // Clear form
            $_POST = [];
            
        } catch (Exception $e) {
            $error = 'Sorry, there was an error sending your message. Please try again or contact us directly.';
            error_log("Contact form error: " . $e->getMessage());
        }
    }
}

$pageTitle = "Contact Us | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Get in touch with Serving Hearts-Uganda. Visit our office in Kasangati or send us a message.">
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
                    <li class="nav-item"><a class="nav-link" href="programs.php">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="impact.php">Impact</a></li>
                    <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
                    <li class="nav-item"><a class="nav-link active" href="contact.php">Contact</a></li>
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
                    <h1 class="display-4 fw-bold text-white mb-3" data-aos="fade-up">Contact Us</h1>
                    <p class="lead text-white" data-aos="fade-up" data-aos-delay="100">
                        We'd love to hear from you. Get in touch with us today!
                    </p>
                    <nav aria-label="breadcrumb" data-aos="fade-up" data-aos-delay="200">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-white">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card contact-info-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-geo-alt-fill text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Visit Our Office</h5>
                            <p class="text-muted mb-2">
                                Wakiso District<br>
                                Kasangati Town Council<br>
                                Uganda
                            </p>
                            <a href="https://maps.google.com" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-map me-1"></i> Get Directions
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card contact-info-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-telephone-fill text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Call Us</h5>
                            <p class="text-muted mb-2">
                                <strong>Phone:</strong><br>
                                +256 XXX XXX XXX
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Office Hours:</strong><br>
                                Mon - Fri: 9:00 AM - 5:00 PM
                            </p>
                            <a href="tel:+256XXXXXXXXX" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-telephone me-1"></i> Call Now
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card contact-info-card h-100 border-0 shadow-sm text-center">
                        <div class="card-body p-4">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-envelope-fill text-primary"></i>
                            </div>
                            <h5 class="fw-bold mb-3">Email Us</h5>
                            <p class="text-muted mb-2">
                                <strong>General Inquiries:</strong><br>
                                info@servinghearts.ug
                            </p>
                            <p class="text-muted mb-2">
                                <strong>Partnerships:</strong><br>
                                partnerships@servinghearts.ug
                            </p>
                            <a href="mailto:info@servinghearts.ug" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-envelope me-1"></i> Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-6" data-aos="fade-right">
                    <h2 class="fw-bold mb-4">Send Us a Message</h2>
                    <p class="text-muted mb-4">
                        Have a question or want to learn more about our programs? Fill out the form below 
                        and we'll get back to you as soon as possible.
                    </p>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" name="phone" 
                                       value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                       placeholder="+256 XXX XXX XXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subject *</label>
                                <select class="form-select" name="subject" required>
                                    <option value="">Select a subject...</option>
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Volunteer Opportunity">Volunteer Opportunity</option>
                                    <option value="Partnership">Partnership Inquiry</option>
                                    <option value="Donation">Donation Question</option>
                                    <option value="Program Information">Program Information</option>
                                    <option value="Media/Press">Media/Press Inquiry</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" name="message" rows="6" required 
                                          placeholder="Tell us more about your inquiry..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacyConsent" required>
                                    <label class="form-check-label small" for="privacyConsent">
                                        I agree to the <a href="#" class="text-primary">Privacy Policy</a> and 
                                        consent to being contacted by Serving Hearts-Uganda.
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send-fill me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Map & Additional Info -->
                <div class="col-lg-6" data-aos="fade-left">
                    <h2 class="fw-bold mb-4">Find Us</h2>
                    
                    <!-- Google Map Placeholder -->
                    <div class="map-container mb-4">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127643.14996485956!2d32.44997!3d0.41944!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x177dbd4d3c3e6e3b%3A0x8a8f8f8f8f8f8f8f!2sKasangati%2C%20Uganda!5e0!3m2!1sen!2sus!4v1234567890"
                            width="100%" 
                            height="350" 
                            style="border:0; border-radius: 10px;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>

                    <div class="card border-0 bg-light">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Why Contact Us?</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Learn about volunteer opportunities
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Inquire about partnership programs
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Request program information
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Schedule a site visit
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check-circle-fill text-primary me-2"></i>
                                    Get support or ask questions
                                </li>
                            </ul>

                            <hr class="my-3">

                            <h6 class="fw-bold mb-3">Connect With Us</h6>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold">Frequently Asked Questions</h2>
                <p class="lead text-muted">Quick answers to common questions</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="0">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    How can I volunteer with Serving Hearts-Uganda?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    You can apply to volunteer by filling out our <a href="get-involved.php#volunteer">volunteer application form</a>. 
                                    We offer opportunities in field work, skills-based volunteering, event support, and more. 
                                    We'll review your application and contact you within 5-7 business days.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    How are donations used?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    All donations go directly to program implementation. We maintain transparency with detailed 
                                    financial reports available upon request. Donations support hygiene kits, training programs, 
                                    startup kits for IGAs, WASH infrastructure, and health education campaigns.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Can I visit your office or project sites?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! We welcome visitors to our office and field sites. Please contact us at least one week in 
                                    advance to schedule a visit. We can arrange site tours, meetings with staff, and community visits 
                                    based on your interests and availability.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    How can my organization partner with SHU?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We welcome partnerships with corporations, foundations, other NGOs, and government agencies. 
                                    Partnership opportunities include program funding, in-kind donations, employee volunteering, 
                                    technical support, and joint program implementation. Contact us at partnerships@servinghearts.ug 
                                    to discuss collaboration opportunities.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    What is your response time for inquiries?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We aim to respond to all inquiries within 24-48 hours during business days (Monday-Friday, 9AM-5PM). 
                                    For urgent matters, please call us directly. Messages received during weekends or holidays will be 
                                    responded to on the next business day.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-muted">
                            Still have questions? <a href="#contactForm" class="text-primary fw-bold">Send us a message</a> 
                            or call us at +256 XXX XXX XXX
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5 bg-gradient-primary text-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Ready to Make a Difference?</h2>
            <p class="lead mb-4">
                Join us in transforming lives across Uganda
            </p>
            <div class="cta-buttons">
                <a href="get-involved.php#donate" class="btn btn-light btn-lg me-3">
                    <i class="bi bi-heart-fill"></i> Donate Now
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
                        A registered non-profit organization dedicated to empowering communities.
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
                        <li class="mb-2"><a href="get-involved.php#donate" class="text-muted text-decoration-none">Donate</a></li>
                        <li class="mb-2"><a href="get-involved.php#volunteer" class="text-muted text-decoration-none">Volunteer</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Contact</h5>
                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill"></i> Wakiso District, Kasangati</li>
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
                    <a href="#" class="text-muted text-decoration-none small">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>