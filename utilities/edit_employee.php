<?php
session_start();
require_once 'con_db.php';
require_once '../components/header.php';

// Redirect to login if not logged in
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {    
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: employees.php");
    exit;
}

$employeeId = $_GET['id'];
$companyId = $_SESSION['companyId'];

// Fetch employee data
$query = "SELECT e.*, ep.phone_number, GROUP_CONCAT(s.skill_name) as skills, 
          GROUP_CONCAT(es.experience_level) as skill_levels
          FROM employees e
          LEFT JOIN employeephone ep ON e.employeeId = ep.employeeId
          LEFT JOIN employeeskills es ON e.employeeId = es.employeeId
          LEFT JOIN skills s ON es.skillId = s.skillId
          WHERE e.employeeId = ? AND e.companyId = ?
          GROUP BY e.employeeId";

$stmt = $db_connection->prepare($query);
$stmt->bind_param("ii", $employeeId, $companyId);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    header("Location: employees.php");
    exit;
}

$skills = $employee['skills'] ? explode(',', $employee['skills']) : [];
$skillLevels = $employee['skill_levels'] ? explode(',', $employee['skill_levels']) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - ConnectCore</title>
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/employees.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php renderHeader('employees'); ?>
    <div class="container">
        <div class="content-wrapper">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="page-header">
                <h1><i class="fas fa-user-edit"></i> Edit Employee</h1>
                <p>Update employee information</p>
            </div>

            <div class="form-container">
                <form action="manage_employees.php" method="POST" class="employee-form">
                    <input type="hidden" name="employeeId" value="<?php echo $employeeId; ?>">
                    
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Employee Name
                        </label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone_number']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="position">
                            <i class="fas fa-briefcase"></i> Position
                        </label>
                        <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="rate">
                            <i class="fa-solid fa-star"></i> Employee Rating
                        </label>
                        <select id="rate" name="rate" required>
                            <option value="1" <?php echo $employee['rate'] === '1' ? 'selected' : ''; ?>>1 - Poor</option>
                            <option value="2" <?php echo $employee['rate'] === '2' ? 'selected' : ''; ?>>2 - Fair</option>
                            <option value="3" <?php echo $employee['rate'] === '3' ? 'selected' : ''; ?>>3 - Good</option>
                            <option value="4" <?php echo $employee['rate'] === '4' ? 'selected' : ''; ?>>4 - Very Good</option>
                            <option value="5" <?php echo $employee['rate'] === '5' ? 'selected' : ''; ?>>5 - Excellent</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="availability">
                            <i class="fas fa-clock"></i> Availability Status
                        </label>
                        <select id="availability" name="availability" required>
                            <option value="available" <?php echo $employee['availability_status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo $employee['availability_status'] === 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-tools"></i> Skills
                        </label>
                        <div id="skillsContainer">
                            <?php for($i = 0; $i < count($skills); $i++): ?>
                                <div class="skill-row">
                                    <input type="text" name="skills[]" value="<?php echo htmlspecialchars($skills[$i]); ?>" placeholder="Skill name" required>
                                    <select name="skill_levels[]" required>
                                        <option value="beginner" <?php echo $skillLevels[$i] === 'beginner' ? 'selected' : ''; ?>>Beginner</option>
                                        <option value="intermediate" <?php echo $skillLevels[$i] === 'intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                        <option value="expert" <?php echo $skillLevels[$i] === 'expert' ? 'selected' : ''; ?>>Expert</option>
                                    </select>
                                    <button type="button" class="remove-skill" onclick="removeSkill(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <button type="button" class="add-skill-btn" onclick="addSkill()">
                            <i class="fas fa-plus"></i> Add Another Skill
                        </button>
                    </div>

                    <input type="hidden" name="action" value="edit">
                    <button type="submit" name="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Update Employee
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/employees.js"></script>
</body>
?>
