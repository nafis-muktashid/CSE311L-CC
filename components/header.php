<?php
function renderHeader($activeTab = '') {
    // Get the base URL path
    $basePath = isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/utilities/') !== false ? '../' : '';
    ?>
    <!-- Header -->
    <header class="main-header">
        <div class="header-left">
            <i class="fas fa-building company-icon"></i>
            <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
        </div>
        <nav class="header-nav">
            <a href="<?php echo $basePath; ?>dashboard.php" class="<?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo $basePath; ?>notifications.php" class="<?php echo $activeTab === 'notifications' ? 'active' : ''; ?>">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
            <a href="<?php echo $basePath; ?>utilities/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </header>
    <?php
}
?>

