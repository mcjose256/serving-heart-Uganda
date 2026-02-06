<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

$success = ''; $error = '';

// 1. Handle Settings Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    try {
        $pdo->beginTransaction();
        
        // Loop through all posted settings
        foreach ($_POST['settings'] as $key => $value) {
            $sql = "UPDATE site_settings SET setting_value = :val, updated_by = :admin, updated_at = NOW() WHERE setting_key = :key";
            executeQuery($pdo, $sql, [
                'val' => $value,
                'admin' => $_SESSION['admin_id'],
                'key' => $key
            ]);
        }
        
        // Handle checkboxes (boolean settings)
        $booleans = ['donation_enabled', 'volunteer_enabled', 'maintenance_mode'];
        foreach ($booleans as $bool) {
            $val = isset($_POST['settings'][$bool]) ? 'true' : 'false';
            executeQuery($pdo, "UPDATE site_settings SET setting_value = ? WHERE setting_key = ?", [$val, $bool]);
        }

        $pdo->commit();
        
        // Log Activity
        executeQuery($pdo, "INSERT INTO activity_logs (admin_id, action, description) VALUES (?, 'settings_update', 'Updated site-wide settings')", [$_SESSION['admin_id']]);
        
        $success = "Settings updated successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Failed to update settings: " . $e->getMessage();
    }
}

// 2. Fetch all current settings into an associative array
$rawSettings = fetchAll($pdo, "SELECT setting_key, setting_value FROM site_settings");
$settings = [];
foreach ($rawSettings as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings | SHU Admin</title>
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
            <li class="nav-item"><a class="nav-link" href="posts.php"><i class="bi bi-newspaper"></i> Blog Posts</a></li>
            <li class="nav-item"><a class="nav-link" href="donations.php"><i class="bi bi-cash-coin"></i> Donations</a></li>
            <li class="nav-item"><a class="nav-link" href="volunteers.php"><i class="bi bi-people"></i> Volunteers</a></li>
            <li class="nav-item"><a class="nav-link active" href="settings.php"><i class="bi bi-gear"></i> Settings</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <h2 class="mb-4"><i class="bi bi-gear me-2"></i>Global Settings</h2>

            <?php if($success): ?><div class="alert alert-success alert-dismissible fade show"><?= $success ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
            <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

            <form method="POST">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#general">General</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#contact">Contact & Social</button></li>
                            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#donations">Donations & Mobile Money</button></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content pt-3">
                            
                            <!-- General Settings -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Organization Name</label>
                                        <input type="text" name="settings[site_name]" class="form-control" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Feature Toggles</label>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="settings[volunteer_enabled]" <?= ($settings['volunteer_enabled'] ?? 'true') == 'true' ? 'checked' : '' ?>>
                                            <label class="form-check-label">Accept Volunteer Applications</label>
                                        </div>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="settings[maintenance_mode]" <?= ($settings['maintenance_mode'] ?? 'false') == 'true' ? 'checked' : '' ?>>
                                            <label class="form-check-label text-danger">Maintenance Mode</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Settings -->
                            <div class="tab-pane fade" id="contact">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Public Email Address</label>
                                        <input type="email" name="settings[site_email]" class="form-control" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Public Phone Number</label>
                                        <input type="text" name="settings[site_phone]" class="form-control" value="<?= htmlspecialchars($settings['site_phone'] ?? '') ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold small">Office Address</label>
                                        <input type="text" name="settings[site_address]" class="form-control" value="<?= htmlspecialchars($settings['site_address'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-bold small">Facebook URL</label>
                                        <input type="text" name="settings[facebook_url]" class="form-control" value="<?= htmlspecialchars($settings['facebook_url'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6 mt-4">
                                        <label class="form-label fw-bold small">Twitter URL</label>
                                        <input type="text" name="settings[twitter_url]" class="form-control" value="<?= htmlspecialchars($settings['twitter_url'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Donation Settings -->
                            <div class="tab-pane fade" id="donations">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" name="settings[donation_enabled]" <?= ($settings['donation_enabled'] ?? 'true') == 'true' ? 'checked' : '' ?>>
                                            <label class="form-check-label fw-bold">Enable Online Donations Section</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Mobile Money Account Number</label>
                                        <input type="text" name="settings[mobile_money_number]" class="form-control" value="<?= htmlspecialchars($settings['mobile_money_number'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Account Name (Display Name)</label>
                                        <input type="text" name="settings[mobile_money_name]" class="form-control" value="<?= htmlspecialchars($settings['mobile_money_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="alert alert-info py-2 small">
                                            <i class="bi bi-info-circle me-2"></i> This info appears on the "Get Involved" page. Ensure the number is correct.
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-footer bg-white py-3">
                        <button type="submit" name="update_settings" class="btn btn-primary px-4">
                            <i class="bi bi-save me-2"></i>Save All Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>