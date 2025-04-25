<?php
session_start();
require_once 'con_db.php';

// Ensure only companies can access this endpoint
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: ../add_employees.php');
    exit;
}

$companyId = $_SESSION['companyId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $db_connection->begin_transaction();
                    
                    // 1. Insert employee
                    $query = "INSERT INTO employees (name, email, position, rate, availability_status, companyId) 
                             VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $db_connection->prepare($query);
                    $stmt->bind_param("sssssi", 
                        $_POST['name'], 
                        $_POST['email'], 
                        $_POST['position'], 
                        $_POST['rate'], 
                        $_POST['availability'],
                        $companyId
                    );
                    $stmt->execute();
                    $employeeId = $db_connection->insert_id;
                    
                    // 2. Insert skills and create employee-skill relationships
                    if (!empty($_POST['skills'])) {
                        foreach ($_POST['skills'] as $key => $skillName) {
                            // Check if skill already exists
                            $checkSkill = "SELECT skillId FROM skills WHERE skill_name = ?";
                            $stmt = $db_connection->prepare($checkSkill);
                            $stmt->bind_param("s", $skillName);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                // Skill exists, get its ID
                                $skill = $result->fetch_assoc();
                                $skillId = $skill['skillId'];
                            } else {
                                // Insert new skill
                                $insertSkill = "INSERT INTO skills (skill_name) VALUES (?)";
                                $stmt = $db_connection->prepare($insertSkill);
                                $stmt->bind_param("s", $skillName);
                                $stmt->execute();
                                $skillId = $db_connection->insert_id;
                            }
                            
                            // Create employee-skill relationship
                            $level = $_POST['skill_levels'][$key];
                            $skillRelation = "INSERT INTO employeeskills (employeeId, skillId, experience_level) VALUES (?, ?, ?)";
                            $stmt = $db_connection->prepare($skillRelation);
                            $stmt->bind_param("iis", $employeeId, $skillId, $level);
                            $stmt->execute();
                        }
                    }

                    // 3. Insert phone number into employee_phone table
                    $phoneQuery = "INSERT INTO employeephone (employeeId, phone_number) VALUES (?, ?)";
                    $stmt = $db_connection->prepare($phoneQuery);
                    $stmt->bind_param("is", $employeeId, $_POST['phone']);
                    $stmt->execute();

                    $db_connection->commit();
                    $_SESSION['success'] = 'Employee added successfully';
                } catch (Exception $e) {
                    $db_connection->rollback();
                    $_SESSION['error'] = $e->getMessage();
                }
                break;
            case 'edit':
                try {
                    $db_connection->begin_transaction();
                    
                    // Update employee
                    $query = "UPDATE employees 
                             SET name = ?, email = ?, position = ?, 
                                 rate = ?, availability_status = ? 
                             WHERE employeeId = ? AND companyId = ?";
                    $stmt = $db_connection->prepare($query);
                    $stmt->bind_param("sssssii", 
                        $_POST['name'], 
                        $_POST['email'], 
                        $_POST['position'], 
                        $_POST['rate'], 
                        $_POST['availability'],
                        $_POST['employeeId'],
                        $companyId
                    );
                    $stmt->execute();

                    // Update phone
                    $query = "UPDATE employeephone 
                             SET phone_number = ? 
                             WHERE employeeId = ?";
                    $stmt = $db_connection->prepare($query);
                    $stmt->bind_param("si", $_POST['phone'], $_POST['employeeId']);
                    $stmt->execute();

                    // Delete existing skills
                    $query = "DELETE FROM employeeskills WHERE employeeId = ?";
                    $stmt = $db_connection->prepare($query);
                    $stmt->bind_param("i", $_POST['employeeId']);
                    $stmt->execute();

                    // Add new skills
                    if (isset($_POST['skills']) && is_array($_POST['skills'])) {
                        $query = "INSERT INTO employeeskills (employeeId, skillId, experience_level) 
                                 VALUES (?, (SELECT skillId FROM skills WHERE skill_name = ?), ?)";
                        $stmt = $db_connection->prepare($query);
                        
                        foreach ($_POST['skills'] as $index => $skill) {
                            $level = $_POST['skill_levels'][$index];
                            $stmt->bind_param("iss", $_POST['employeeId'], $skill, $level);
                            $stmt->execute();
                        }
                    }

                    $db_connection->commit();
                    $_SESSION['success'] = 'Employee updated successfully';
                    
                } catch (Exception $e) {
                    $db_connection->rollback();
                    $_SESSION['error'] = $e->getMessage();
                }
                
                header('Location: ../employees.php');
                exit;
        }
    }
    header('Location: ../add_employees.php');
    exit;
}



