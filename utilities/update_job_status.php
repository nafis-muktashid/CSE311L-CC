<?php
session_start();
require_once 'con_db.php';

if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: ../my_job_postings.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jobId']) && isset($_POST['action'])) {
    $jobId = $_POST['jobId'];
    $action = $_POST['action'];
    $companyId = $_SESSION['companyId'];

    try {
        // Verify the job belongs to the company
        $checkQuery = "SELECT jobId FROM jobpostings WHERE jobId = ? AND companyId = ?";
        $stmt = $db_connection->prepare($checkQuery);
        $stmt->bind_param("ii", $jobId, $companyId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Job not found or unauthorized');
        }

        // Update job status
        $status = ($action === 'close') ? 'closed' : 'open';
        $updateQuery = "UPDATE jobpostings SET status = ? WHERE jobId = ?";
        $stmt = $db_connection->prepare($updateQuery);
        $stmt->bind_param("si", $status, $jobId);
        $stmt->execute();

        $_SESSION['success'] = 'Job status updated successfully';

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    header('Location: ../my_job_postings.php');
    exit;
}

$_SESSION['error'] = 'Invalid request';
header('Location: ../my_job_postings.php');
exit;