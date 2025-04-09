<?php
session_start();
require_once 'con_db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to apply']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$jobId = $data['jobId'];
$type = $data['type'];
$offerRate = isset($data['offerRate']) ? floatval($data['offerRate']) : null;

// Get user's company ID
$userCompanyId = $_SESSION['companyId'];

// Check if already applied
$checkQuery = "SELECT applicationId FROM jobapplications 
              WHERE applyingCompanyId = ? AND jobId = ?";
$stmt = $db_connection->prepare($checkQuery);
$stmt->bind_param("ii", $userCompanyId, $jobId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You have already applied to this job']);
    exit;
}

// Insert application
$query = "INSERT INTO jobapplications (applyingCompanyId, jobId, application_type, offer_rate, status) 
          VALUES (?, ?, ?, ?, 'pending')";
$stmt = $db_connection->prepare($query);
$stmt->bind_param("iiss", $userCompanyId, $jobId, $type, $offerRate);

if ($stmt->execute()) {
    $message = $type === 'apply' ? 'Application submitted successfully' : 'Rate offer submitted successfully';
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error submitting application']);
}
?>
