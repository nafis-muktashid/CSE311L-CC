<?php
session_start();
require_once 'con_db.php';

// Ensure only companies can access this endpoint
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: ../employees.php');
    exit;
}

$companyId = $_SESSION['companyId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employeeId'])) {
    $employeeId = $_POST['employeeId'];
    
    try {
        $db_connection->begin_transaction();

        // Verify the employee belongs to the company
        $checkQuery = "SELECT employeeId FROM employees WHERE employeeId = ? AND companyId = ?";
        $stmt = $db_connection->prepare($checkQuery);
        $stmt->bind_param("ii", $employeeId, $companyId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Employee not found or unauthorized');
        }

        // Delete from employeeskills
        $query = "DELETE FROM employeeskills WHERE employeeId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();

        // Delete from employeephone
        $query = "DELETE FROM employeephone WHERE employeeId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();

        // Delete from employeeassignments
        $query = "DELETE FROM employeeassignments WHERE employeeId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("i", $employeeId);
        $stmt->execute();

        // Finally delete the employee
        $query = "DELETE FROM employees WHERE employeeId = ? AND companyId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("ii", $employeeId, $companyId);
        $stmt->execute();

        $db_connection->commit();
        $_SESSION['success'] = 'Employee deleted successfully';

    } catch (Exception $e) {
        $db_connection->rollback();
        $_SESSION['error'] = $e->getMessage();
    }

    header('Location: ../employees.php');
    exit;
}

$_SESSION['error'] = 'Invalid request';
header('Location: ../employees.php');
exit;