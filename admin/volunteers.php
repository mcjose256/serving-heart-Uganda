<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

$success = ''; $error = '';

// Handle Actions (Status Update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $vId = $_POST['volunteer_id'] ?? null;

    if ($vId) {
        try {
            if ($action === 'delete') {
                executeQuery($pdo, "DELETE FROM volunteers WHERE id = :id", ['id' => $vId]);
                $success = 'Application deleted successfully.';
                $desc = "Deleted volunteer application ID: $vId";
            } else {
                $sql = "UPDATE volunteers SET application_status = :status, reviewed_by = :admin, reviewed_at = NOW() WHERE id = :id";
                executeQuery($pdo, $sql, ['status' => $action, 'admin' => $_SESSION['admin_id'], 'id' => $vId]);
                $success = "Application " . ucfirst($action) . " successfully.";
                $desc = "Changed status of volunteer ID: $vId to $action";
            }
            // Log Activity
            executeQuery($pdo, "INSERT INTO activity_logs (admin_id, action, description) VALUES (?, ?, ?)", [$_SESSION['admin_id'], 'volunteer_mgmt', $desc]);
        } catch (Exception $e) { $error = 'Action failed.'; }
    }
}

// Filters
$statusFilter = $_GET['status'] ?? 'all';
$sql = "SELECT * FROM volunteers";
if ($statusFilter !== 'all') {
    $sql .= " WHERE application_status = :status";
    $volunteers = fetchAll($pdo, $sql . " ORDER BY created_at DESC", ['status' => $statusFilter]);
} else {
    $volunteers = fetchAll($pdo, $sql . " ORDER BY created_at DESC");
}

// Stats
$pendingCount = fetchOne($pdo, "SELECT COUNT(*) as c FROM volunteers WHERE application_status = 'pending'")['c'];
$approvedCount = fetchOne($pdo, "SELECT COUNT(*) as c FROM volunteers WHERE application_status = 'approved'")['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Volunteers | SHU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php if(file_exists('includes/navbar.php')) include 'includes/navbar.php'; ?>

    <div class="sidebar">
        <div class="sidebar-header"><h5 class="mb-0">Navigation</h5></div>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link active" href="volunteers.php"><i class="bi bi-people"></i> Volunteers</a></li>
            <li class="nav-item"><a class="nav-link" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
            <li class="nav-item"><a class="nav-link" href="posts.php"><i class="bi bi-newspaper"></i> Blog Posts</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <?php if($success): ?><div class="alert alert-success alert-dismissible fade show"><?= $success ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-people me-2"></i>Volunteers</h2>
            </div>

            <!-- Stats Summary -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-white border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Pending Review</h6>
                            <h3 class="mb-0"><?= $pendingCount ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase small">Approved Volunteers</h6>
                            <h3 class="mb-0"><?= $approvedCount ?></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item"><a class="nav-link <?= $statusFilter==='all'?'active':'' ?>" href="volunteers.php?status=all">All Applications</a></li>
                <li class="nav-item"><a class="nav-link <?= $statusFilter==='pending'?'active':'' ?>" href="volunteers.php?status=pending">Pending</a></li>
                <li class="nav-item"><a class="nav-link <?= $statusFilter==='approved'?'active':'' ?>" href="volunteers.php?status=approved">Approved</a></li>
            </ul>

            <!-- Data Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($volunteers as $v): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($v['full_name']) ?></strong></td>
                                    <td><small><?= htmlspecialchars($v['email']) ?><br><?= htmlspecialchars($v['phone']) ?></small></td>
                                    <td><span class="badge bg-light text-dark border"><?= ucfirst($v['availability'] ?: 'Not set') ?></span></td>
                                    <td>
                                        <span class="badge bg-<?= $v['application_status']==='approved'?'success':($v['application_status']==='pending'?'warning':'danger') ?>">
                                            <?= ucfirst($v['application_status']) ?>
                                        </span>
                                    </td>
                                    <td><small><?= date('M d, Y', strtotime($v['created_at'])) ?></small></td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewVolunteer(<?= $v['id'] ?>)"><i class="bi bi-eye"></i></button>
                                            <?php if($v['application_status'] === 'pending'): ?>
                                                <button class="btn btn-sm btn-success" onclick="updateStatus(<?= $v['id'] ?>, 'approved')"><i class="bi bi-check-lg"></i></button>
                                                <button class="btn btn-sm btn-warning" onclick="updateStatus(<?= $v['id'] ?>, 'rejected')"><i class="bi bi-x-lg"></i></button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-danger" onclick="updateStatus(<?= $v['id'] ?>, 'delete')"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Volunteer Application Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Form -->
    <form id="actionForm" method="POST" style="display:none;">
        <input type="hidden" name="volunteer_id" id="actionId">
        <input type="hidden" name="action" id="actionValue">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateStatus(id, status) {
            if(confirm('Are you sure you want to ' + status + ' this application?')) {
                document.getElementById('actionId').value = id;
                document.getElementById('actionValue').value = status;
                document.getElementById('actionForm').submit();
            }
        }

        function viewVolunteer(id) {
            const modal = new bootstrap.Modal(document.getElementById('viewModal'));
            const content = document.getElementById('modalContent');
            content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
            modal.show();

            fetch('get_volunteer_details.php?id=' + id)
                .then(r => r.json())
                .then(data => {
                    content.innerHTML = `
                        <div class="row">
                            <div class="col-md-6 mb-3"><strong>Full Name:</strong><br>${data.full_name}</div>
                            <div class="col-md-6 mb-3"><strong>Email/Phone:</strong><br>${data.email} / ${data.phone}</div>
                            <div class="col-md-6 mb-3"><strong>Location:</strong><br>${data.location || 'N/A'}</div>
                            <div class="col-md-6 mb-3"><strong>Education:</strong><br>${data.education_level || 'N/A'}</div>
                            <div class="col-12 mb-3"><strong>Skills:</strong><p class="bg-light p-2 small">${data.skills || 'N/A'}</p></div>
                            <div class="col-12 mb-3"><strong>Motivation:</strong><p class="bg-light p-2 small">${data.motivation}</p></div>
                            <div class="col-md-6 mb-3"><strong>Availability:</strong><br>${data.availability} (${data.hours_per_week || '0'} hrs/week)</div>
                            <div class="col-md-6 mb-3"><strong>Experience:</strong><br>${data.previous_experience || 'None'}</div>
                        </div>
                        ${data.reviewed_at ? `<div class="mt-3 pt-3 border-top small text-muted">Reviewed by ${data.reviewer_name} on ${data.reviewed_at}</div>` : ''}
                    `;
                });
        }
    </script>
</body>
</html>