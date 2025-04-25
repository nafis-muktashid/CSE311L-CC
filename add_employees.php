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
    <title>Manage Employees - ConnectCore</title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/employees.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php renderHeader('add_employees'); ?>
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
                <h1><i class="fas fa-user-plus"></i> Add New Employee</h1>
                <p>Add a new idle employee to your company profile</p>
            </div>

            <div class="form-container">
                <form action="./utilities/manage_employees.php" method="POST" class="employee-form">
                    <div class="form-group">
                        <label for="name">
                            <i class="fas fa-user"></i> Employee Name
                        </label>
                        <input type="text" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                            <i class="fas fa-phone"></i> Phone
                        </label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="position">
                            <i class="fas fa-briefcase"></i> Position
                        </label>
                        <input type="text" id="position" name="position" required>
                    </div>

                    <div class="form-group">
                        <label for="rate">
                            <i class="fa-solid fa-star"></i> Employee Rating
                        </label>
                        <select id="rate" name="rate" required>
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="availability">
                            <i class="fas fa-clock"></i> Availability Status
                        </label>
                        <select id="availability" name="availability" required>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <i class="fas fa-tools"></i> Skills
                        </label>
                        <div id="skillsContainer">
                            <div class="skill-row">
                                <input type="text" name="skills[]" placeholder="Skill name" required>
                                <select name="skill_levels[]" required>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="expert">Expert</option>
                                </select>
                                <button type="button" class="remove-skill" onclick="removeSkill(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="add-skill-btn" onclick="addSkill()">
                            <i class="fas fa-plus"></i> Add Another Skill
                        </button>
                    </div>

                    <input type="hidden" name="action" value="add">
                    <button type="submit" name="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Save Employee
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="./js/employees.js"></script>
</body>
</html>




