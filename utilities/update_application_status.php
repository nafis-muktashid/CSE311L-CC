<?php
require_once __DIR__ . '/con_db.php';  // Use __DIR__ for reliable path resolution

function updateApplicationStatus($applicationId, $status, $employeeId = null) {
    global $db_connection;
    
    try {
        $db_connection->begin_transaction();

        $query = "UPDATE jobapplications SET status = ? WHERE applicationId = ?";
        $stmt = $db_connection->prepare($query);
        $stmt->bind_param("si", $status, $applicationId);
        $stmt->execute();

        if ($status === 'accepted') {
            $getDetailsQuery = "SELECT jp.companyId as hiringCompanyId, ja.applyingCompanyId, ja.jobId 
                              FROM jobapplications ja 
                              JOIN jobpostings jp ON ja.jobId = jp.jobId 
                              WHERE ja.applicationId = ?";
            $stmt = $db_connection->prepare($getDetailsQuery);
            $stmt->bind_param("i", $applicationId);
            $stmt->execute();
            $result = $stmt->get_result();
            $details = $result->fetch_assoc();

            $createContract = "INSERT INTO contracts (start_date, end_date, status, hiringCompanyId, applyingCompanyId, jobId) 
                             VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'active', ?, ?, ?)";
            $stmt = $db_connection->prepare($createContract);
            $stmt->bind_param("iii", $details['hiringCompanyId'], $details['applyingCompanyId'], $details['jobId']);
            $stmt->execute();
            $contractId = $db_connection->insert_id;

            if ($employeeId) {
                $updateEmployee = "UPDATE employees SET availability_status = 'unavailable' WHERE employeeId = ?";
                $stmt = $db_connection->prepare($updateEmployee);
                $stmt->bind_param("i", $employeeId);
                $stmt->execute();

                $createAssignment = "INSERT INTO employeeassignments (contractId, companyId, employeeId) 
                                   VALUES (?, ?, ?)";
                $stmt = $db_connection->prepare($createAssignment);
                $stmt->bind_param("iii", $contractId, $details['applyingCompanyId'], $employeeId);
                $stmt->execute();
            }
        }

        $db_connection->commit();
        $_SESSION['success'] = $status === 'accepted' ? 'Application accepted and employee assigned successfully' : 'Application rejected successfully';
        
    } catch (Exception $e) {
        $db_connection->rollback();
        $_SESSION['error'] = $e->getMessage();
    }
}

?>

