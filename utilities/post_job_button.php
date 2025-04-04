<?php

session_start();
require_once './con_db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_POST['post_job'])) {
    $job_title = $_POST['job_title'];
    $required_skill = $_POST['required_skill'];
    $job_details = $_POST['job_details'];
    $rate = $_POST['rate'];

    $email = $_SESSION['email'];
    $query = "SELECT companyId FROM companies WHERE email = '$email'";
    $result = $db_connection->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $companyId = $row['companyId'];

        $query = "INSERT INTO jobpostings (job_title, required_skill, job_details, rate, companyId) 
                  VALUES ('$job_title', '$required_skill', '$job_details', '$rate', '$companyId')";

        if ($db_connection->query($query)) {
            $_SESSION['success_message'] = "Job posted successfully.";
        } else {
            $_SESSION['error_message'] = "Error posting job.";
        }
    } else {
        $_SESSION['error_message'] = "Company not found.";
    }

    header("Location: ../post_job.php");
    exit;
}

?>
