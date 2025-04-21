<?php
// Ensure no caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();

// Verify session and company ID if user is a company
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'company') {
    if (!isset($_SESSION['companyId'])) {
        // Re-fetch company ID if missing
        require_once './utilities/con_db.php';
        $email = $_SESSION['email'];
        $companyQuery = "SELECT companyId FROM companies WHERE email = ?";
        $stmt = $db_connection->prepare($companyQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $_SESSION['companyId'] = $row['companyId'];
        }
    }
}

require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Modify query to exclude current company's posts
$query = "SELECT j.*, c.name as company_name 
          FROM jobpostings j 
          LEFT JOIN companies c ON j.companyId = c.companyId 
          WHERE 1=1";

// Add condition to exclude current company's posts if user is a company
$params = [];
$types = "";

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'company') {
    $query .= " AND j.companyId != ?";
    $params[] = $_SESSION['companyId'];
    $types .= "i";
}

$query .= " ORDER BY j.posted_time DESC";

$stmt = $db_connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Postings - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/job_postings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('job_postings'); ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-list-alt"></i> Available Employee Offers</h1>
                <p>Browse through opportunities from other companies</p>
            </div>

            <div class="jobs-container">
                <?php if($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="job-card">
                            <div class="job-header">
                                <h2><?php echo htmlspecialchars($row['job_title']); ?></h2>
                                <span class="company-name">
                                    <i class="fas fa-building"></i>
                                    <?php echo htmlspecialchars($row['company_name']); ?>
                                </span>
                            </div>
                            <div class="job-details">
                                <p><i class="fas fa-tools"></i> <?php echo htmlspecialchars($row['required_skill']); ?></p>
                                <p><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($row['rate']); ?>/hr</p>
                                <p><i class="fas fa-clock"></i> Posted: <?php echo date('M d, Y', strtotime($row['posted_time'])); ?></p>
                            </div>
                            <div class="job-description">
                                <p><?php echo htmlspecialchars(substr($row['job_details'], 0, 150)) . '...'; ?></p>
                            </div>
                            <div class="job-actions">
                                <button class="view-details" onclick="showJobDetails(
                                    '<?php echo $row['jobId']; ?>', 
                                    '<?php echo str_replace("'", "\\'", htmlspecialchars($row['job_title'])); ?>', 
                                    '<?php echo str_replace("'", "\\'", htmlspecialchars($row['job_details'])); ?>', 
                                    '<?php echo str_replace("'", "\\'", htmlspecialchars($row['company_name'])); ?>',
                                    '<?php echo $row['companyId']; ?>',
                                    '<?php echo $row['rate']; ?>',
                                    '<?php echo isset($_SESSION['companyId']) ? $_SESSION['companyId'] : 'null'; ?>'
                                )">
                                    <i class="fas fa-eye"></i> View Details
                                </button>
                                <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] !== 'company'): ?>
                                    <button class="apply-btn">
                                        <i class="fas fa-paper-plane"></i> Apply Now
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-jobs">
                        <i class="fas fa-folder-open"></i>
                        <h2>No Job Postings Available</h2>
                        <p>Check back later for new opportunities from other companies</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Job Details Modal -->
    <div id="jobDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalJobTitle"></h2>
            <h3 id="modalCompanyName"></h3>
            <p id="modalJobDetails"></p>
            
            <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'company'): ?>
            <div id="applicationSection" class="application-section">
                <div class="rate-offer">
                    <label for="offerRate">Your Rate Offer ($/hr):</label>
                    <input type="number" id="offerRate" min="1" step="0.01">
                </div>
                <div class="action-buttons">
                    <button id="applyButton" class="apply-modal-btn">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </button>
                    <button id="offerButton" class="offer-btn">
                        <i class="fas fa-dollar-sign"></i> Submit Rate Offer
                    </button>
                </div>
                <p id="applicationMessage" class="application-message"></p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="./js/job_postings.js"></script>
</body>
</html>
