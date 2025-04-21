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
    <title>Notifications - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php renderHeader('notifications'); ?>
        
        <div class="notifications-content">
            <div class="page-header">
                <h1><i class="fas fa-bell"></i> Notifications</h1>
                <p>Stay updated with your latest activities</p>
            </div>
            
            <!-- Notification content will go here -->
        </div>
    </div>
</body>
</html>