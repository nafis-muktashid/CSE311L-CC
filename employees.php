<?php

session_start();
require_once './utilities/con_db.php';
require_once './components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {    
    header("Location: login.php");
    exit;
}

$companyId = $_SESSION['companyId'];
// Fetch employees with their skills
$query = "SELECT e.*, ep.phone_number, GROUP_CONCAT(CONCAT(s.skill_name, ' (', es.experience_level, ')') SEPARATOR ', ') as skills
          FROM employees e          
          LEFT JOIN employeephone ep ON e.employeeId = ep.employeeId
          LEFT JOIN employeeskills es ON e.employeeId = es.employeeId          
          LEFT JOIN skills s ON es.skillId = s.skillId
          WHERE e.companyId = ?          
          GROUP BY e.employeeId
          ORDER BY e.name";

$stmt = $db_connection->prepare($query);
$stmt->bind_param("i", $companyId);
$stmt->execute();$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
    <head>    
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">    
        <title>View Employees - ConnectCore</title>
        <link rel="stylesheet" href="./css/base.css">    
        <link rel="stylesheet" href="./css/employee_management.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body>    
        
        <?php renderHeader('employees'); ?>        
        <div class="container">
            
            <div class="content-wrapper empwrap">            
                <div class="page-header">
                    <h1><i class="fas fa-users"></i> Your Employees</h1>                
                    <p>Manage and view all your employees</p>
                </div>
                
                <!-- Add search bar -->
                <div class="search-container">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="employeeSearch" placeholder="Search employees by name..." class="search-input">
                    </div>
                </div>
                
                <div class="employees-container">                
                    <?php if($result->num_rows > 0): ?>
                        <?php while($employee = $result->fetch_assoc()): ?>                        
                            <div class="employee-card">
                                <div class="employee-header">                                
                                    <h2><?php echo htmlspecialchars($employee['name']); ?></h2>
                                    <span class="position"><?php echo htmlspecialchars($employee['position']); ?></span>                            
                                </div>
                            
                                <div class="employee-details">                                
                                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($employee['email']); ?></p>
                                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($employee['phone_number']); ?></p>                                
                                    <p><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($employee['rate']); ?></p>
                                    <p><i class="fas fa-clock"></i> Status: <?php echo htmlspecialchars($employee['availability_status']); ?></p>                                
                                    <?php if($employee['skills']): ?>
                                        <p><i class="fas fa-tools"></i> Skills: <?php echo htmlspecialchars($employee['skills']); ?></p>                                
                                    <?php endif; ?>
                                </div>                            
                            
                                <div class="employee-actions">
                                    <a href="utilities/edit_employee.php?id=<?php echo $employee['employeeId']; ?>" class="edit-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="utilities/delete_employee.php" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                        <input type="hidden" name="employeeId" value="<?php echo $employee['employeeId']; ?>">
                                        <button type="submit" class="delete-btn">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>                        
                            </div>
                        <?php endwhile; ?>                
                    <?php else: ?>
                        <div class="no-employees">                        
                            <i class="fas fa-users-slash"></i>
                            <h2>No Employees Found</h2>                        
                            <p>You haven't added any employees yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>    
        </div>
        <script src="./js/employees.js"></script>
    </body>
</html>
















































