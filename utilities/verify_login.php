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
            $_SESSION['user_type'] = $user['user_type'];  // Added this line

            if($user['user_type'] === 'company'){
                header("Location: ../dashboard.php"); //login success
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
