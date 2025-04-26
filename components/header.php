<?php
/**
 * Header Component
 * 
 * This component renders the main navigation header that appears across all authenticated pages
 * in the ConnectCore application. It includes the company logo, company name, and main navigation
 * links (Dashboard, Notifications, and Logout).
 * 
 * Usage:
 * require_once './components/header.php';
 * renderHeader('current_page_name');
 */

/**
 * Renders the header navigation bar
 * 
 * @param string $activeTab The current active tab/page identifier
 *                         Possible values: 'dashboard', 'notifications', 'post_job',
 *                         'employees', 'add_employees', 'job_postings'
 */
function renderHeader($activeTab = '') {
    // Handle path differences when accessed from utilities folder
    // This ensures assets and links work correctly regardless of the current page location
    $basePath = isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/utilities/') !== false ? '../' : '';
    ?>
    <!-- Main Navigation Header -->
    <header class="main-header">
        <!-- Left section: Company Identity -->
        <div class="header-left">
            <i class="fas fa-building company-icon"></i>
            <!-- Display company name from session - XSS protected -->
            <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
        </div>

        <!-- Navigation Menu -->
        <nav class="header-nav">
            <!-- Dashboard Link -->
            <a href="<?php echo $basePath; ?>dashboard.php" 
               class="<?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <!-- Notifications Link -->
            <a href="<?php echo $basePath; ?>notifications.php" 
               class="<?php echo $activeTab === 'notifications' ? 'active' : ''; ?>">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>

            <!-- Logout Link -->
            <a href="<?php echo $basePath; ?>utilities/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </header>
    <?php
}
?>

