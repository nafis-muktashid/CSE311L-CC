<?php
function renderHeader($activeTab = '') {
    ?>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <i class="fas fa-building company-icon"></i>
            <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
        </div>
        <nav class="header-nav">
            <a href="dashboard.php" class="<?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="notifications.php" class="<?php echo $activeTab === 'notifications' ? 'active' : ''; ?>">
                <i class="fas fa-bell"></i> Notifications
            </a>
            <a href="./utilities/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>
    <?php
}
?>

