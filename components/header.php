<?php
function renderHeader($activeTab = '') {
    ?>
    <header>
        <div class="header-left">
            <i class="fas fa-building company-icon"></i>
            <h2><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
        </div>
        <nav>
            <a href="dashboard.php" class="<?php echo $activeTab === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="post_job.php" class="<?php echo $activeTab === 'post_job' ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle"></i> Post a Job
            </a>
            <a href="job_postings.php" class="<?php echo $activeTab === 'job_postings' ? 'active' : ''; ?>">
                <i class="fas fa-list"></i> View Jobs
            </a>
            <a href="./utilities/logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </header>
    <?php
}
?>