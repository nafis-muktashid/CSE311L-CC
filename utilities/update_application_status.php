<?php
session_start();
require_once 'con_db.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
        throw new Exception('Unauthorized access');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        throw new Exception('Invalid request data');
    }

    $applicationId = $data['applicationId'];
    $status = $data['status'];

    // Verify that this company owns the job posting
    $query = "SELECT jp.companyId 
              FROM jobapplications ja
              JOIN jobpostings jp ON ja.jobId = jp.jobId
              WHERE ja.applicationId = ?";

    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("i", $applicationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobData = $result->fetch_assoc();

    if (!$jobData || $jobData['companyId'] != $_SESSION['companyId']) {
        throw new Exception('Unauthorized action');
    }

    // Update application status
    $updateQuery = "UPDATE jobapplications SET status = ? WHERE applicationId = ?";
    $stmt = $db_connection->prepare($updateQuery);
    $stmt->bind_param("si", $status, $applicationId);

    if (!$stmt->execute()) {
        throw new Exception('Error updating application status');
    }

    // Add notification
    $notificationQuery = "INSERT INTO notifications (userId, message, read_status, time)
                         SELECT ja.applyingCompanyId, 
                                CONCAT('Your application has been ', ?, ' for job: ', jp.job_title),
                                0,
                                CURRENT_TIMESTAMP
                         FROM jobapplications ja
                         JOIN jobpostings jp ON ja.jobId = jp.jobId
                         WHERE ja.applicationId = ?";
    
    $stmt = $db_connection->prepare($notificationQuery);
    $stmt->bind_param("si", $status, $applicationId);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Application status updated successfully']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

