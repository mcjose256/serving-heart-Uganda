<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../includes/db.php';

$success = '';
$error = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Confirm donation
    if ($action === 'confirm') {
        $donationId = $_POST['donation_id'] ?? null;
        if ($donationId) {
            try {
                $sql = "UPDATE donations SET payment_status = 'confirmed', confirmed_by = :admin_id, confirmed_at = NOW() WHERE id = :id";
                executeQuery($pdo, $sql, ['admin_id' => $_SESSION['admin_id'], 'id' => $donationId]);
                
                // Log activity
                $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, 'confirm_donation', :desc)";
                executeQuery($pdo, $logSql, [
                    'admin_id' => $_SESSION['admin_id'],
                    'desc' => "Confirmed donation ID: $donationId"
                ]);
                
                $success = 'Donation confirmed successfully!';
            } catch (Exception $e) {
                $error = 'Failed to confirm donation.';
            }
        }
    }
    
    // Delete donation
    if ($action === 'delete') {
        $donationId = $_POST['donation_id'] ?? null;
        if ($donationId) {
            try {
                executeQuery($pdo, "DELETE FROM donations WHERE id = :id", ['id' => $donationId]);
                $success = 'Donation deleted successfully!';
                
                // Log activity
                $logSql = "INSERT INTO activity_logs (admin_id, action, description) VALUES (:admin_id, 'delete_donation', :desc)";
                executeQuery($pdo, $logSql, [
                    'admin_id' => $_SESSION['admin_id'],
                    'desc' => "Deleted donation ID: $donationId"
                ]);
            } catch (Exception $e) {
                $error = 'Failed to delete donation.';
            }
        }
    }
}

// Fetch donations with filters
$statusFilter = $_GET['status'] ?? 'all';
$sql = "SELECT d.*, a.username as confirmed_by_name FROM donations d 
        LEFT JOIN admins a ON d.confirmed_by = a.id";

if ($statusFilter !== 'all') {
    $sql .= " WHERE d.payment_status = :status";
}

$sql .= " ORDER BY d.created_at DESC";

if ($statusFilter !== 'all') {
    $donations = fetchAll($pdo, $sql, ['status' => $statusFilter]);
} else {
    $donations = fetchAll($pdo, $sql);
}

// Calculate summary
$totalDonations = fetchOne($pdo, "SELECT COUNT(*) as count, SUM(amount) as total FROM donations WHERE payment_status = 'confirmed'");
$pendingCount = fetchOne($pdo, "SELECT COUNT(*) as count FROM donations WHERE payment_status = 'pending'")['count'];

$pageTitle = "Donations Management";
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
    <?php if(file_exists('includes/navbar.php')) include 'includes/navbar.php'; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header"><h5 class="mb-0">Navigation</h5></div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="posts.php"><i class="bi bi-newspaper"></i> Blog Posts</a></li>
            <li class="nav-item"><a class="nav-link active" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
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

            <h2 class="mb-4"><i class="bi bi-cash-coin me-2"></i>Donations Management</h2>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Total Confirmed</h6>
                            <h3 class="mb-0">UGX <?= number_format($totalDonations['total'] ?? 0) ?></h3>
                            <small><?= $totalDonations['count'] ?> donations</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Pending Confirmation</h6>
                            <h3 class="mb-0"><?= $pendingCount ?></h3>
                            <small>Requires attention</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase small">This Month</h6>
                            <h3 class="mb-0"><?php 
                                $thisMonth = fetchOne($pdo, "SELECT COUNT(*) as count FROM donations WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())");
                                echo $thisMonth['count'];
                            ?></h3>
                            <small>New entries</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'all' ? 'active' : '' ?>" href="donations.php?status=all">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'pending' ? 'active' : '' ?>" href="donations.php?status=pending">
                        Pending <?php if ($pendingCount > 0): ?><span class="badge bg-warning"><?= $pendingCount ?></span><?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $statusFilter === 'confirmed' ? 'active' : '' ?>" href="donations.php?status=confirmed">Confirmed</a>
                </li>
            </ul>

            <!-- Donations Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Donor</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($donations)): ?>
                                    <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($donation['donor_name']) ?></strong><br>
                                                <small class="text-muted"><?= htmlspecialchars($donation['donor_phone']) ?></small>
                                            </td>
                                            <td><strong class="text-success">UGX <?= number_format($donation['amount']) ?></strong></td>
                                            <td><span class="badge bg-secondary"><?= ucfirst($donation['payment_method']) ?></span></td>
                                            <td><code><?= htmlspecialchars($donation['transaction_reference'] ?: 'N/A') ?></code></td>
                                            <td>
                                                <span class="badge bg-<?= $donation['payment_status'] === 'confirmed' ? 'success' : 'warning' ?>">
                                                    <?= ucfirst($donation['payment_status']) ?>
                                                </span>
                                            </td>
                                            <td><small><?= date('M d, Y', strtotime($donation['created_at'])) ?></small></td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewDonation(<?= $donation['id'] ?>)" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <?php if ($donation['payment_status'] === 'pending'): ?>
                                                        <button class="btn btn-sm btn-success" onclick="confirmDonation(<?= $donation['id'] ?>)" title="Confirm Payment">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteDonation(<?= $donation['id'] ?>)" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center py-4 text-muted">No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Donation Modal -->
    <div class="modal fade" id="viewDonationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Donation Record Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="donationDetailContent">
                    <div class="text-center py-3"><div class="spinner-border text-primary"></div></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Action Forms -->
    <form id="confirmForm" method="POST" style="display:none;">
        <input type="hidden" name="action" value="confirm">
        <input type="hidden" name="donation_id" id="confirmDonationId">
    </form>
    <form id="deleteForm" method="POST" style="display:none;">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="donation_id" id="deleteDonationId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDonation(id) {
            if (confirm('Verify and confirm this donation payment?')) {
                document.getElementById('confirmDonationId').value = id;
                document.getElementById('confirmForm').submit();
            }
        }
        
        function deleteDonation(id) {
            if (confirm('Delete this record permanently?')) {
                document.getElementById('deleteDonationId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }
        
        function viewDonation(id) {
            const modal = new bootstrap.Modal(document.getElementById('viewDonationModal'));
            const container = document.getElementById('donationDetailContent');
            container.innerHTML = '<div class="text-center py-3"><div class="spinner-border text-primary"></div></div>';
            modal.show();

            fetch('get_donation_details.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data && !data.error) {
                        container.innerHTML = `
                            <div class="mb-3 border-bottom pb-2"><strong>Donor Name:</strong><br>${data.donor_name}</div>
                            <div class="mb-3 border-bottom pb-2"><strong>Contact:</strong><br>${data.donor_email} / ${data.donor_phone}</div>
                            <div class="mb-3 border-bottom pb-2"><strong>Amount:</strong><br><span class="text-success fw-bold">UGX ${new Intl.NumberFormat().format(data.amount)}</span></div>
                            <div class="mb-3 border-bottom pb-2"><strong>Reference:</strong><br><code>${data.transaction_reference || 'Not Provided'}</code></div>
                            <div class="mb-3 border-bottom pb-2"><strong>Donor Message:</strong><br><p class="bg-light p-2 small mt-1 rounded">${data.message || 'No message.'}</p></div>
                            <div class="mb-1"><strong>Status:</strong> <span class="badge bg-${data.payment_status === 'confirmed' ? 'success' : 'warning'}">${data.payment_status.toUpperCase()}</span></div>
                            ${data.confirmed_at ? `<div class="small text-muted mt-3 pt-2 border-top">Confirmed by ${data.admin_name} on ${data.confirmed_at}</div>` : ''}
                        `;
                    } else {
                        container.innerHTML = '<div class="alert alert-danger">Could not load details.</div>';
                    }
                })
                .catch(() => {
                    container.innerHTML = '<div class="alert alert-danger">Server error. Please check if get_donation_details.php exists.</div>';
                });
        }
    </script>
</body>
</html>