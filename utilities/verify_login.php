<?php

session_start();
require_once 'con_db.php';

if(isset($_POST)){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $db_connection->query("SELECT * FROM `users` WHERE `email` = '$email'");
    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
       
        if($password === $user['password']){
            // Store all necessary session variables
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];

            if($user['user_type'] === 'company'){
                // Get and store company ID
                $companyQuery = "SELECT companyId FROM companies WHERE email = ?";
                $stmt = $db_connection->prepare($companyQuery);
                $stmt->bind_param("s", $user['email']);
                $stmt->execute();
                $result = $stmt->get_result();
                if($row = $result->fetch_assoc()) {
                    $_SESSION['companyId'] = $row['companyId'];
                }
                header("Location: ../dashboard.php");
            }else{
                header("Location: ../admin_dashboard.php");
            }
            exit();
        }else{
            $_SESSION['error_message'] = "Incorrect Password.";
        }
    }else{
        $_SESSION['error_message'] = "Company not found.";
    }

    $db_connection->close();
    header("Location: ../login.php");
    exit();
}

?>
