<?php
session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit;
}

// Fetch company's own job postings
$email = $_SESSION['email'];
$query = "SELECT j.*, 
                 (SELECT COUNT(*) FROM jobapplications WHERE jobId = j.jobId) as application_count 
          FROM jobpostings j 
          WHERE j.companyId = (SELECT companyId FROM companies WHERE email = ?)
          ORDER BY j.posted_time DESC";

$stmt = $db_connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Postings - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/job_postings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('job_postings'); ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-briefcase"></i> My Job Postings</h1>
                <p>View and manage all your posted jobs</p>
            </div>

            <div class="jobs-container">
                <?php if($result->num_rows > 0): ?>
                    <?php while($job = $result->fetch_assoc()): ?>
                        <div class="job-card">
                            <div class="job-header">
                                <h2><?php echo htmlspecialchars($job['job_title']); ?></h2>
                                <span class="status-badge <?php echo strtolower($job['status']); ?>">
                                    <?php echo htmlspecialchars($job['status']); ?>
                                </span>
                            </div>
                            <div class="job-details">
                                <p><i class="fas fa-tools"></i> Required Skill: <?php echo htmlspecialchars($job['required_skill']); ?></p>
                                <p><i class="fas fa-dollar-sign"></i> Rate: $<?php echo htmlspecialchars($job['rate']); ?>/hr</p>
                                <p><i class="fas fa-users"></i> Applications: <?php echo $job['application_count']; ?></p>
                                <p><i class="fas fa-clock"></i> Posted: <?php echo date('M j, Y', strtotime($job['posted_time'])); ?></p>
                                <div class="job-description">
                                    <p><?php echo nl2br(htmlspecialchars($job['job_details'])); ?></p>
                                </div>
                            </div>
                            <?php if($job['status'] === 'open'): ?>
                                <div class="job-actions">
                                    <form method="POST" action="utilities/update_job_status.php" onsubmit="return confirm('Are you sure you want to close this job posting?');">
                                        <input type="hidden" name="jobId" value="<?php echo $job['jobId']; ?>">
                                        <input type="hidden" name="action" value="close">
                                        <button type="submit" class="close-btn">
                                            <i class="fas fa-times-circle"></i> Close Job
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-jobs">
                        <i class="fas fa-briefcase-medical"></i>
                        <h2>No Job Postings Found</h2>
                        <p>You haven't posted any jobs yet.</p>
                        <a href="post_job.php" class="post-job-btn">
                            <i class="fas fa-plus"></i> Post a New Job
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>