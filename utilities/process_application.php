<?php
session_start();
require_once 'con_db.php';

header('Content-Type: application/json');

try {
    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        throw new Exception('Please log in to apply');
    }

    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new Exception('Invalid request data');
    }

    $jobId = $data['jobId'];
    $type = $data['type'];
    $offerRate = isset($data['offerRate']) ? floatval($data['offerRate']) : null;
    $userCompanyId = $_SESSION['companyId'];

    // First, get the job posting details to check ownership
    $jobQuery = "SELECT companyId FROM jobpostings WHERE jobId = ?";
    $stmt = $db_connection->prepare($jobQuery);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $jobResult = $stmt->get_result();
    $jobData = $jobResult->fetch_assoc();

    if (!$jobData) {
        throw new Exception('Job posting not found');
    }

    // Check if the user is trying to apply to their own job posting
    if ($jobData['companyId'] == $userCompanyId) {
        throw new Exception('You cannot apply to your own job posting');
    }

    // Check if already applied
    $checkQuery = "SELECT applicationId, status FROM jobapplications 
                  WHERE applyingCompanyId = ? AND jobId = ?";
    $stmt = $db_connection->prepare($checkQuery);
    $stmt->bind_param("ii", $userCompanyId, $jobId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $existingApplication = $result->fetch_assoc();
        if ($existingApplication['status'] !== 'withdrawn') {
            throw new Exception('You have already applied to this job');
        }
    }

    // Insert application
    $query = "INSERT INTO jobapplications (applyingCompanyId, jobId, offer_rate, status, application_type) 
              VALUES (?, ?, ?, 'pending', ?)";
    $stmt = $db_connection->prepare($query);
    $applicationType = ($type === 'offer') ? 'offer' : 'apply';
    $stmt->bind_param("iids", $userCompanyId, $jobId, $offerRate, $applicationType);

    if (!$stmt->execute()) {
        throw new Exception('Error submitting application');
    }

    // Send notification
    $notificationQuery = "INSERT INTO notifications (userId, message, read_status, time) 
                         VALUES (?, ?, 0, CURRENT_TIMESTAMP)";
    $stmt = $db_connection->prepare($notificationQuery);
    
    $message = $offerRate 
        ? "New rate offer of $" . $offerRate . " received for your job posting"
        : "New application received for your job posting";
    
    $stmt->bind_param("is", 
        $jobData['companyId'], 
        $message
    );
    $stmt->execute();

    $message = $type === 'apply' ? 'Application submitted successfully' : 'Rate offer submitted successfully';
    echo json_encode(['success' => true, 'message' => $message]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>




