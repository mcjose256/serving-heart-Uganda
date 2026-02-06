<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

// Fetch dashboard statistics
try {
    // Total counts
    $totalPosts = fetchOne($pdo, "SELECT COUNT(*) as count FROM posts")['count'];
    $publishedPosts = fetchOne($pdo, "SELECT COUNT(*) as count FROM posts WHERE status = 'published'")['count'];
    $totalDonations = fetchOne($pdo, "SELECT COUNT(*) as count FROM donations")['count'];
    $confirmedDonations = fetchOne($pdo, "SELECT COUNT(*) as count FROM donations WHERE payment_status = 'confirmed'")['count'];
    $pendingDonations = fetchOne($pdo, "SELECT COUNT(*) as count FROM donations WHERE payment_status = 'pending'")['count'];
    $totalVolunteers = fetchOne($pdo, "SELECT COUNT(*) as count FROM volunteers")['count'];
    $pendingVolunteers = fetchOne($pdo, "SELECT COUNT(*) as count FROM volunteers WHERE application_status = 'pending'")['count'];
    $unreadMessages = fetchOne($pdo, "SELECT COUNT(*) as count FROM contact_messages WHERE status = 'unread'")['count'];
    
    // Total donation amount
    $donationAmount = fetchOne($pdo, "SELECT SUM(amount) as total FROM donations WHERE payment_status = 'confirmed'")['total'] ?? 0;
    
    // Recent donations
    $recentDonations = fetchAll($pdo, "SELECT * FROM donations ORDER BY created_at DESC LIMIT 5");
    
    // Recent volunteers
    $recentVolunteers = fetchAll($pdo, "SELECT * FROM volunteers ORDER BY created_at DESC LIMIT 5");
    
    // Recent messages
    $recentMessages = fetchAll($pdo, "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5");
    
    // Recent activity
    $recentActivity = fetchAll($pdo, "SELECT al.*, a.username FROM activity_logs al LEFT JOIN admins a ON al.admin_id = a.id ORDER BY al.created_at DESC LIMIT 10");
    
} catch (Exception $e) {
    error_log("Dashboard error: " . $e->getMessage());
}

$pageTitle = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | SHU Admin</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Admin Custom CSS -->
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
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php" target="_blank">
                            <i class="bi bi-globe"></i> View Website
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                            <?= htmlspecialchars($_SESSION['admin_name']) ?>
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
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="posts.php">
                    <i class="bi bi-newspaper"></i> Blog Posts
                    <?php if ($totalPosts > 0): ?>
                        <span class="badge bg-primary"><?= $totalPosts ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="donations.php">
                    <i class="bi bi-cash-coin"></i> Donations
                    <?php if ($pendingDonations > 0): ?>
                        <span class="badge bg-warning"><?= $pendingDonations ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="volunteers.php">
                    <i class="bi bi-people"></i> Volunteers
                    <?php if ($pendingVolunteers > 0): ?>
                        <span class="badge bg-info"><?= $pendingVolunteers ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="messages.php">
                    <i class="bi bi-envelope"></i> Messages
                    <?php if ($unreadMessages > 0): ?>
                        <span class="badge bg-danger"><?= $unreadMessages ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="impact-stats.php">
                    <i class="bi bi-bar-chart"></i> Impact Stats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="settings.php">
                    <i class="bi bi-gear"></i> Site Settings
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-card">
                        <h2 class="mb-1">Welcome back, <?= htmlspecialchars($_SESSION['admin_name']) ?>! 👋</h2>
                        <p class="text-muted mb-0">Here's what's happening with Serving Hearts-Uganda today.</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-primary">
                        <div class="stat-icon">
                            <i class="bi bi-newspaper"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?= $publishedPosts ?></h3>
                            <p>Published Posts</p>
                            <small class="text-muted"><?= $totalPosts ?> total</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-success">
                        <div class="stat-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="stat-content">
                            <h3>UGX <?= number_format($donationAmount) ?></h3>
                            <p>Total Donations</p>
                            <small class="text-muted"><?= $confirmedDonations ?> confirmed</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-info">
                        <div class="stat-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?= $totalVolunteers ?></h3>
                            <p>Volunteer Applications</p>
                            <small class="text-muted"><?= $pendingVolunteers ?> pending</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card stat-card-warning">
                        <div class="stat-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <h3><?= $unreadMessages ?></h3>
                            <p>Unread Messages</p>
                            <small class="text-muted">Requires attention</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <a href="posts.php?action=create" class="btn btn-primary w-100">
                                        <i class="bi bi-plus-circle me-2"></i>New Post
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="donations.php?status=pending" class="btn btn-warning w-100">
                                        <i class="bi bi-clock-history me-2"></i>Pending Donations
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="volunteers.php?status=pending" class="btn btn-info w-100">
                                        <i class="bi bi-person-check me-2"></i>Review Volunteers
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="messages.php?status=unread" class="btn btn-danger w-100">
                                        <i class="bi bi-envelope-open me-2"></i>Read Messages
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Data -->
            <div class="row">
                <!-- Recent Donations -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Recent Donations</h5>
                            <a href="donations.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentDonations)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentDonations as $donation): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($donation['donor_name']) ?></h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar3 me-1"></i>
                                                        <?= date('M d, Y', strtotime($donation['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <strong class="text-success">UGX <?= number_format($donation['amount']) ?></strong>
                                                    <br>
                                                    <span class="badge bg-<?= $donation['payment_status'] == 'confirmed' ? 'success' : 'warning' ?>">
                                                        <?= ucfirst($donation['payment_status']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No donations yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Volunteer Applications -->
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-people me-2"></i>Recent Volunteers</h5>
                            <a href="volunteers.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentVolunteers)): ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($recentVolunteers as $volunteer): ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1"><?= htmlspecialchars($volunteer['full_name']) ?></h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-envelope me-1"></i>
                                                        <?= htmlspecialchars($volunteer['email']) ?>
                                                    </small>
                                                </div>
                                                <span class="badge bg-<?= $volunteer['application_status'] == 'approved' ? 'success' : ($volunteer['application_status'] == 'pending' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($volunteer['application_status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No volunteer applications yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Log -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-activity me-2"></i>Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recentActivity)): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Action</th>
                                                <th>Description</th>
                                                <th>IP Address</th>
                                                <th>Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recentActivity as $activity): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($activity['username'] ?? 'System') ?></td>
                                                    <td>
                                                        <span class="badge bg-info"><?= htmlspecialchars($activity['action']) ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($activity['description']) ?></td>
                                                    <td><small class="text-muted"><?= htmlspecialchars($activity['ip_address']) ?></small></td>
                                                    <td><small class="text-muted"><?= date('M d, H:i', strtotime($activity['created_at'])) ?></small></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted text-center py-4">No recent activity.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>