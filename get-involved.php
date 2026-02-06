<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to access flash messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db.php';

$pageTitle = "Get Involved | Serving Hearts-Uganda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><span class="text-primary">Serving Hearts</span>-Uganda</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="programs.php">Programs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="get-involved.php">Get Involved</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="page-header" style="background: linear-gradient(135deg, #0d6efd 0%, #764ba2 100%);">
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold">Support Our Work</h1>
            <p class="lead">Donate or Volunteer to make a change today.</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row g-5">
            <!-- Donation Info -->
            <div class="col-lg-5" id="donate">
                <div class="card border-0 shadow-lg p-4">
                    <h3 class="fw-bold mb-3"><i class="bi bi-heart-fill text-danger"></i> Donate</h3>
                    <p class="text-muted">Direct Mobile Money donations are the fastest way to support our field activities.</p>
                    <div class="bg-light p-3 rounded mb-4 border-start border-primary border-4">
                        <p class="mb-1 fw-bold">MTN / AIRTEL:</p>
                        <h4 class="text-primary fw-bold">0700 000 000</h4>
                        <p class="small mb-0">Name: Serving Hearts Uganda</p>
                    </div>
                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle me-2"></i> After sending, please contact us with your reference for a receipt.
                    </div>
                </div>
            </div>

            <!-- Volunteer Form -->
            <div class="col-lg-7" id="volunteer">
                <div class="card border-0 shadow-sm p-4">
                    <h3 class="fw-bold mb-4">Volunteer Application</h3>
                    
                    <!-- FLASH MESSAGE DISPLAY START -->
                    <?php 
                    $msg = getFlashMessage(); 
                    if ($msg): 
                    ?>
                        <div class="alert alert-<?= $msg['type'] ?> alert-dismissible fade show" role="alert">
                            <?= $msg['message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <!-- FLASH MESSAGE DISPLAY END -->

                    <form action="process-volunteer.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Full Name</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <!-- ADDED PHONE FIELD (Required by DB) -->
                            <div class="col-12">
                                <label class="form-label small fw-bold">Phone Number (WhatsApp Preferred)</label>
                                <input type="text" name="phone" class="form-control" placeholder="+256..." required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Skills / Profession</label>
                                <input type="text" name="skills" class="form-control" placeholder="e.g. Health Worker, Teacher, ICT">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Why do you want to join us?</label>
                                <textarea name="motivation" class="form-control" rows="4" required placeholder="Tell us about your motivation..."></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 py-3">Submit Application</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 text-center">
        <p>&copy; <?= date('Y') ?> Serving Hearts-Uganda Limited. All rights reserved.</p>
    </footer>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>