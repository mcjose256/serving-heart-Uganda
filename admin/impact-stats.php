<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

$success = ''; $error = '';

// 1. Handle Form Submissions (Add / Update / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Save or Update
    if ($action === 'save') {
        $id = $_POST['stat_id'] ?? null;
        $name = sanitize($_POST['metric_name']);
        $value = sanitize($_POST['metric_value']);
        $desc = sanitize($_POST['metric_description']);
        $cat = $_POST['metric_category'];
        $order = intval($_POST['display_order']);
        $active = isset($_POST['is_active']) ? 1 : 0;

        try {
            if ($id) {
                // Update
                $sql = "UPDATE impact_stats SET metric_name = ?, metric_value = ?, metric_description = ?, metric_category = ?, display_order = ?, is_active = ?, updated_by = ?, updated_at = NOW() WHERE id = ?";
                executeQuery($pdo, $sql, [$name, $value, $desc, $cat, $order, $active, $_SESSION['admin_id'], $id]);
                $success = "Statistic updated successfully.";
            } else {
                // Insert
                $sql = "INSERT INTO impact_stats (metric_name, metric_value, metric_description, metric_category, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
                executeQuery($pdo, $sql, [$name, $value, $desc, $cat, $order, $active]);
                $success = "New statistic added.";
            }
            // Log Activity
            executeQuery($pdo, "INSERT INTO activity_logs (admin_id, action, description) VALUES (?, 'impact_update', ?)", [$_SESSION['admin_id'], "Updated impact metric: $name"]);
        } catch (Exception $e) { $error = "Error saving data."; }
    }

    // Delete
    if ($action === 'delete') {
        $id = $_POST['stat_id'];
        executeQuery($pdo, "DELETE FROM impact_stats WHERE id = ?", [$id]);
        $success = "Statistic removed.";
    }
}

// 2. Fetch Data
$stats = fetchAll($pdo, "SELECT * FROM impact_stats ORDER BY display_order ASC");

// 3. Fetch specific stat for editing
$editStat = null;
if (isset($_GET['edit'])) {
    $editStat = fetchOne($pdo, "SELECT * FROM impact_stats WHERE id = ?", [$_GET['edit']]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Impact Stats Management | SHU Admin</title>
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
            <li class="nav-item"><a class="nav-link active" href="impact-stats.php"><i class="bi bi-bar-chart"></i> Impact Stats</a></li>
            <li class="nav-item"><a class="nav-link" href="volunteers.php"><i class="bi bi-people"></i> Volunteers</a></li>
            <li class="nav-item"><a class="nav-link" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Impact Statistics</h2>

            <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <div class="row">
                <!-- Form Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white fw-bold">
                            <?= $editStat ? 'Edit Metric' : 'Add New Metric' ?>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="save">
                                <?php if($editStat): ?>
                                    <input type="hidden" name="stat_id" value="<?= $editStat['id'] ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Metric Name (e.g. Girls Reached)</label>
                                    <input type="text" name="metric_name" class="form-control" value="<?= $editStat['metric_name'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Metric Value (e.g. 5,000+)</label>
                                    <input type="text" name="metric_value" class="form-control" value="<?= $editStat['metric_value'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select name="metric_category" class="form-select">
                                        <?php 
                                        $categories = ['general', 'mhm', 'iga', 'wash', 'hiv'];
                                        foreach($categories as $cat): 
                                            $selected = (isset($editStat) && $editStat['metric_category'] == $cat) ? 'selected' : '';
                                        ?>
                                            <option value="<?= $cat ?>" <?= $selected ?>><?= strtoupper($cat) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Short Description</label>
                                    <textarea name="metric_description" class="form-control" rows="2"><?= $editStat['metric_description'] ?? '' ?></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold">Display Order</label>
                                        <input type="number" name="display_order" class="form-control" value="<?= $editStat['display_order'] ?? '0' ?>">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label small fw-bold">Active</label>
                                        <div class="form-check form-switch mt-1">
                                            <input class="form-check-input" type="checkbox" name="is_active" <?= (!isset($editStat) || $editStat['is_active']) ? 'checked' : '' ?>>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100"><?= $editStat ? 'Update Metric' : 'Save Metric' ?></button>
                                <?php if($editStat): ?>
                                    <a href="impact-stats.php" class="btn btn-light w-100 mt-2">Cancel</a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- List Column -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order</th>
                                            <th>Metric</th>
                                            <th>Value</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($stats as $s): ?>
                                        <tr>
                                            <td><?= $s['display_order'] ?></td>
                                            <td><strong><?= htmlspecialchars($s['metric_name']) ?></strong></td>
                                            <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['metric_value']) ?></span></td>
                                            <td><small class="text-uppercase"><?= $s['metric_category'] ?></small></td>
                                            <td>
                                                <i class="bi bi-circle-fill <?= $s['is_active'] ? 'text-success' : 'text-danger' ?> small"></i>
                                                <?= $s['is_active'] ? 'Active' : 'Hidden' ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="?edit=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                                <form method="POST" class="d-inline" onsubmit="return confirm('Delete this metric?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="stat_id" value="<?= $s['id'] ?>">
                                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>