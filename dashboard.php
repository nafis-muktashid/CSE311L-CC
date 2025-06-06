<?php
// Add these headers at the top of the file
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Fetch company jobs if logged in as a company
$email = $_SESSION['email'];
$query = "SELECT * FROM jobpostings WHERE companyId = (SELECT companyId FROM companies WHERE email = '$email') AND status = 'open'";
$result = $db_connection->query($query);

// Fetch employee count
$employeeQuery = "SELECT COUNT(*) as employee_count FROM employees WHERE companyId = (SELECT companyId FROM companies WHERE email = '$email')";
$employeeResult = $db_connection->query($employeeQuery);
$employeeCount = $employeeResult->fetch_assoc()['employee_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ConnectCore</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Header -->
    <?php renderHeader('dashboard'); ?>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar-container">
            <?php require_once './components/sidebar.php'; ?>
        </div>
        
        <!-- Main Content -->
        <div class="dashboard-container">
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <i class="fas fa-chart-line stats-icon"></i>
                    <h1>Welcome to Your Dashboard</h1>
                    <p>Manage your employees, job postings and track applications</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-briefcase"></i>
                        <h3>Active Jobs</h3>
                        <p class="stat-number"><?php echo $result->num_rows; ?></p>
                        <a href="my_job_postings.php" class="manage-link">View Jobs <i class="fas fa-arrow-right"></i></a>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3>Employees</h3>
                        <p class="stat-number"><?php echo $employeeCount; ?></p>
                        <a href="employees.php" class="manage-link">View Employees <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Recent Jobs -->
                <div class="recent-jobs">
                    <h2><i class="fas fa-history"></i> Recent Job Postings</h2>
                    <div class="jobs-list">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="job-card">
                                <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                                <p><i class="fas fa-tools"></i> <?php echo htmlspecialchars($row['required_skill']); ?></p>
                                <p><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($row['rate']); ?>/hr</p>
                                <p class="job-status">Status: <?php echo htmlspecialchars($row['status']);?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
