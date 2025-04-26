<?php
/**
 * Sidebar Navigation Component
 * 
 * This component renders the main sidebar navigation that appears on all authenticated company pages
 * in the ConnectCore application. It provides access to core company functionalities including
 * employee management, job posting, and application management.
 * 
 * Usage:
 * require_once './components/sidebar.php';
 * 
 * Required Variables:
 * @global string $activeTab - Must be set in the parent page to highlight current section
 *                            This variable determines which navigation item is marked as active
 */

// Verify required variable exists
if (!isset($activeTab)) {
    $activeTab = ''; // Default to no active tab if not set
}
?>

<!-- Main Sidebar Navigation -->
<nav class="sidebar">
    <div class="sidebar-nav">
        <!-- Employee Management Section -->
        <a href="add_employees.php" class="nav-link <?php echo $activeTab === 'add_employee' ? 'active' : ''; ?>">
            <i class="fas fa-user-plus"></i>
            <span>Add an Employee</span>
        </a>
        <a href="employees.php" class="nav-link <?php echo $activeTab === 'employees' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Employees</span>
        </a>

        <!-- Job Management Section -->
        <a href="post_job.php" class="nav-link <?php echo $activeTab === 'post_job' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            <span>Post an Offer</span>
        </a>
        <a href="job_postings.php" class="nav-link <?php echo $activeTab === 'job_postings' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i>
            <span>Find Employees</span>
        </a>
        <a href="my_job_postings.php" class="nav-link <?php echo $activeTab === 'my_job_postings' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i>
            <span>View My Offers</span>
        </a>

        <!-- Application Management Section -->
        <a href="manage_applications.php" class="nav-link <?php echo $activeTab === 'manage_applications' ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i>
            <span>Manage Applications</span>
        </a>
    </div>
</nav>

<?php
/**
 * Valid $activeTab values:
 * - 'add_employee': Add Employee page
 * - 'employees': Employee listing page
 * - 'post_job': Job posting form
 * - 'job_postings': Available jobs listing
 * - 'my_job_postings': Company's posted jobs
 * - 'manage_applications': Application management page
 * 
 * Dependencies:
 * - Font Awesome 6.0.0 for icons
 * - sidebar.css for styling
 * - base.css for common styles
 * 
 * Integration Notes:
 * - This sidebar is typically included within a container div with class 'sidebar-container'
 * - The parent page must set $activeTab before including this component
 * - The sidebar is responsive and collapses on mobile devices (<768px)
 * 
 * Security:
 * - No direct user input is processed in this component
 * - All links are static and internal to the application
 */

