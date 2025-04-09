<?php

session_start();
require_once './con_db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['post_job'])) {
    // Sanitize inputs
    $job_title = mysqli_real_escape_string($db_connection, $_POST['job_title']);
    $required_skill = mysqli_real_escape_string($db_connection, $_POST['required_skill']);
    $job_details = mysqli_real_escape_string($db_connection, $_POST['job_details']);
    $rate = floatval($_POST['rate']);

    // Validate inputs
    if (empty($job_title) || empty($required_skill) || empty($job_details) || $rate <= 0) {
        $_SESSION['error_message'] = "All fields are required and rate must be greater than 0.";
        header("Location: ../post_job.php");
        exit;
    }

    $email = $_SESSION['email'];
    
    // Use prepared statement to prevent SQL injection
    $query = "SELECT companyId FROM companies WHERE email = ?";
    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyId = $row['companyId'];

        // Use prepared statement for insertion
        $insert_query = "INSERT INTO jobpostings (job_title, required_skill, job_details, rate, companyId) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $db_connection->prepare($insert_query);
        $stmt->bind_param("sssdi", $job_title, $required_skill, $job_details, $rate, $companyId);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Job posted successfully.";
        } else {
            $_SESSION['error_message'] = "Error posting job: " . $db_connection->error;
        }
    } else {
        $_SESSION['error_message'] = "Company not found.";
    }

    header("Location: ../post_job.php");
    exit;
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: ../post_job.php");
    exit;
}

?>
