<nav class="sidebar">
    <div class="sidebar-nav">
        <a href="employees.php" class="nav-link <?php echo $activeTab === 'employees' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Employees</span>
        </a>
        <a href="post_job.php" class="nav-link <?php echo $activeTab === 'post_job' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i>
            <span>Post a Job</span>
        </a>
        <a href="job_postings.php" class="nav-link <?php echo $activeTab === 'job_postings' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i>
            <span>View Jobs</span>
        </a>
        <a href="manage_applications.php" class="nav-link <?php echo $activeTab === 'manage_applications' ? 'active' : ''; ?>">
            <i class="fas fa-tasks"></i>
            <span>Manage Applications</span>
        </a>
    </div>
</nav>

