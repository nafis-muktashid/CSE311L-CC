<?php
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
$query = "SELECT * FROM jobpostings WHERE companyId = (SELECT companyId FROM companies WHERE email = '$email')";
$result = $db_connection->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('dashboard'); ?>
        
        <div class="dashboard-content">
            <div class="welcome-section">
                <i class="fas fa-chart-line stats-icon"></i>
                <h1>Welcome to Your Dashboard</h1>
                <p>Manage your job postings and track applications</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-briefcase"></i>
                    <h3>Active Jobs</h3>
                    <p class="stat-number"><?php echo $result->num_rows; ?></p>
                </div>
                <!-- Add more stat cards as needed -->
            </div>

            <div class="recent-jobs">
                <h2><i class="fas fa-history"></i> Recent Job Postings</h2>
                <div class="jobs-list">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="job-card">
                            <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                            <p><i class="fas fa-tools"></i> <?php echo htmlspecialchars($row['required_skill']); ?></p>
                            <p><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($row['rate']); ?>/hr</p>
                            <p class="job-status">Status: Active</p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
