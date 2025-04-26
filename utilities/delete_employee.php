<?php
/**
 * Employee Deletion Endpoint
 * 
 * Handles the deletion of employee records and related data.
 * Requires company authentication and validates ownership.
 * Uses transaction to ensure data consistency across related tables.
 * 
 * Tables affected:
 * - employees
 * - employeeskills
 * - employeephone
 * - employeeassignments
 * 
 * @method POST
 * @param int employeeId Required: ID of employee to delete
 * @return Redirects to employees.php with status message
 */

session_start();
require_once 'con_db.php';

// Authentication check
if (!isset($_SESSION['email']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error'] = 'Unauthorized access';
    header('Location: ../employees.php');
    exit;
}

// Input validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['employeeId'])) {
    $_SESSION['error'] = 'Invalid request';
    header('Location: ../employees.php');
    exit;
}

$companyId = $_SESSION['companyId'];
$employeeId = filter_input(INPUT_POST, 'employeeId', FILTER_VALIDATE_INT);

if (!$employeeId) {
    $_SESSION['error'] = 'Invalid employee ID';
    header('Location: ../employees.php');
    exit;
}

try {
    $db_connection->begin_transaction();

    // Verify employee ownership
    $checkQuery = "SELECT employeeId FROM employees 
                  WHERE employeeId = ? AND companyId = ?";
    $stmt = $db_connection->prepare($checkQuery);
    $stmt->bind_param("ii", $employeeId, $companyId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Employee not found or unauthorized');
    }

    // Delete related records in correct order to maintain referential integrity
    $deletions = [
        ['table' => 'employeeskills', 'params' => 'i'],
        ['table' => 'employeephone', 'params' => 'i'],
        ['table' => 'employeeassignments', 'params' => 'i']
    ];

    foreach ($deletions as $deletion) {
        $query = "DELETE FROM {$deletion['table']} WHERE employeeId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param($deletion['params'], $employeeId);
        $stmt->execute();
    }

    // Delete employee record
    $query = "DELETE FROM employees WHERE employeeId = ? AND companyId = ?";
    $stmt = $db_connection->prepare($query);
    $stmt->bind_param("ii", $employeeId, $companyId);
    $stmt->execute();

    // Commit transaction
    $db_connection->commit();
    $_SESSION['success'] = 'Employee deleted successfully';

} catch (Exception $e) {
    // Rollback on error
    $db_connection->rollback();
    $_SESSION['error'] = $e->getMessage();
    error_log("Employee deletion error: " . $e->getMessage());
} finally {
    // Always redirect back to employees page
    header('Location: ../employees.php');
    exit;
}
