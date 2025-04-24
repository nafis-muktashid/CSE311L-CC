<?php
session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Fetch notifications for the current company
$companyId = $_SESSION['companyId'];
$query = "SELECT * FROM notifications 
          WHERE userId = ? 
          ORDER BY time DESC";

$stmt = $db_connection->prepare($query);
$stmt->bind_param("i", $companyId);
$stmt->execute();
$notifications = $stmt->get_result();

// Mark all unread notifications as read
$updateQuery = "UPDATE notifications 
                SET read_status = 1 
                WHERE userId = ? AND read_status = 0";
$stmt = $db_connection->prepare($updateQuery);
$stmt->bind_param("i", $companyId);
$stmt->execute();
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
    <?php renderHeader('notifications'); ?>
    <div class="container">
        
        <div class="notifications-content">
            <div class="page-header">
                <h1><i class="fas fa-bell"></i> Notifications</h1>
                <p>Stay updated with your latest activities</p>
            </div>
            
            <div class="notifications-list">
                <?php if($notifications->num_rows > 0): ?>
                    <?php while($notification = $notifications->fetch_assoc()): ?>
                        <div class="notification-card <?php echo $notification['read_status'] ? 'read' : 'unread'; ?>">
                            <div class="notification-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-message"><?php echo htmlspecialchars($notification['message']); ?></p>
                                <span class="notification-time">
                                    <?php echo date('M d, Y h:i A', strtotime($notification['time'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <h2>No Notifications</h2>
                        <p>You're all caught up!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
