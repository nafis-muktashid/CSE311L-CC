<?php
session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    header("Location: login.php");
    exit;
}

$companyId = $_SESSION['companyId'];

// Updated query to determine application type based on offer_rate
$query = "SELECT 
            ja.*,
            jp.job_title,
            jp.rate as posted_rate,
            c.name as applying_company_name,
            CASE 
                WHEN ja.offer_rate IS NOT NULL THEN 'offer'
                ELSE 'apply'
            END as application_type,
            ja.offer_rate
          FROM jobapplications ja
          JOIN jobpostings jp ON ja.jobId = jp.jobId
          JOIN companies c ON ja.applyingCompanyId = c.companyId
          WHERE jp.companyId = ?
          ORDER BY ja.apply_time DESC";

$stmt = $db_connection->prepare($query);
$stmt->bind_param("i", $companyId);
$stmt->execute();
$applications = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/manage_applications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('manage_applications'); ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-tasks"></i> Manage Applications</h1>
                <p>Review and manage applications for your job postings</p>
            </div>

            <div class="applications-container">
                <?php if($applications->num_rows > 0): ?>
                    <?php while($app = $applications->fetch_assoc()): ?>
                        <div class="application-card">
                            <div class="application-header">
                                <h2><?php echo htmlspecialchars($app['job_title']); ?></h2>
                                <span class="company-name">
                                    <i class="fas fa-building"></i>
                                    <?php echo htmlspecialchars($app['applying_company_name']); ?>
                                </span>
                            </div>
                            
                            <div class="application-details">
                                <p>
                                    <i class="fas fa-clock"></i> 
                                    Applied: <?php echo date('M d, Y', strtotime($app['apply_time'])); ?>
                                </p>
                                <p>
                                    <i class="fas fa-file-alt"></i>
                                    Type: <?php echo ucfirst($app['application_type']); ?>
                                </p>
                                <?php if($app['application_type'] === 'offer'): ?>
                                    <p>
                                        <i class="fas fa-dollar-sign"></i>
                                        Original Rate: $<?php echo number_format($app['posted_rate'], 2); ?>/hr
                                    </p>
                                    <p>
                                        <i class="fas fa-dollar-sign"></i>
                                        Offered Rate: $<?php echo number_format($app['offer_rate'], 2); ?>/hr
                                    </p>
                                <?php endif; ?>
                                <p>
                                    <i class="fas fa-info-circle"></i>
                                    Status: <span class="status-<?php echo $app['status']; ?>">
                                        <?php echo ucfirst($app['status']); ?>
                                    </span>
                                </p>
                            </div>

                            <?php if($app['status'] === 'pending'): ?>
                                <div class="application-actions">
                                    <button onclick="updateApplicationStatus(<?php echo $app['applicationId']; ?>, 'accepted')" class="accept-btn">
                                        <i class="fas fa-check"></i> Accept
                                    </button>
                                    <button onclick="updateApplicationStatus(<?php echo $app['applicationId']; ?>, 'rejected')" class="reject-btn">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-applications">
                        <i class="fas fa-folder-open"></i>
                        <h2>No Applications Yet</h2>
                        <p>You haven't received any applications for your job postings</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="./js/manage_applications.js"></script>
</body>
</html>
