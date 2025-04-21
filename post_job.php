<?php
session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/post_job.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('post_job'); ?>
        
        <div class="content-wrapper">
            <div class="page-header">
                <h1><i class="fas fa-plus-circle"></i> Post a New Offer</h1>
                <p>Create a new offer for your idle employee</p>
            </div>

            <div class="form-container">
                <form action="./utilities/post_job_button.php" method="POST" class="job-form">
                    <div class="form-group">
                        <label for="job_title">
                            <i class="fas fa-briefcase"></i> Job Title
                        </label>
                        <input type="text" id="job_title" name="job_title" required>
                    </div>

                    <div class="form-group">
                        <label for="required_skill">
                            <i class="fas fa-tools"></i> Required Skill
                        </label>
                        <input type="text" id="required_skill" name="required_skill" required>
                    </div>

                    <div class="form-group">
                        <label for="rate">
                            <i class="fas fa-dollar-sign"></i> Hourly Rate
                        </label>
                        <input type="number" id="rate" name="rate" min="0" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="job_details">
                            <i class="fas fa-file-alt"></i> Job Description
                        </label>
                        <textarea id="job_details" name="job_details" rows="6" required></textarea>
                    </div>

                    <input type="hidden" name="post_job" value="1">

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Post Job
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
