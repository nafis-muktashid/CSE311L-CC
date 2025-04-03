<?php

session_start();
require_once 'con_db.php';

//input validator function
function validate_input($condition, $error_msg){
    if($condition){
        $_SESSION['error_message'] = $error_msg;
        header("Location: ../reg.php");
        exit;
    }
}

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $industry_name = $_POST['industry'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $company_phone = $_POST['phone'];
    $company_address = $_POST['address'];
    
    //Input Sanitizer
    $company_address = htmlspecialchars($company_address);
    $company_phone = htmlspecialchars($company_phone);
    
    //Input VAlidator
    validate_input(strlen($password) < 8, "Password must be at least 8 characters.");
    validate_input(strlen($company_phone) != 11, "Phone number must be 11 digits.");
    
    $query0 = "SELECT * FROM companies WHERE email = '$email'";
    $res = $db_connection->query($query0);
    
    if($res -> num_rows > 0){
        $_SESSION['error_message'] =  "This email is already in use.";
        $db_connection->close();
        header("Location: ../reg.php");
        exit;
    }
    
    //Create account
    $password = password_hash($_POST['$password'], PASSWORD_DEFAULT);
    
    $query1 = "INSERT INTO users(`password`, `name`, `email`, `phone_num`, `user_type`) 
            VALUES ('$password', '$name', '$email', '$company_phone', 'company')";

    $query2 = "UPDATE companies
            SET industry_type = '$industry_name', address = '$company_address'
            WHERE email = '$email'";

    if($db_connection -> query($query1)){
        sleep(1);
        
        $query_check = "SELECT * FROM `companies` WHERE `email` = '$email'";
        $res_check = $db_connection->query($query_check);

        if($res_check->num_rows > 0){
            if($db_connection->query($query2)){
                $_SESSION['success_message'] = "Your account has been created.";
            }else{
                $_SESSION['error_message'] = "Error inserting company infos.";
            }
        }else{
            $_SESSION['error_message'] = "Company not found in companies table.";
        }
        
    }else{
        $_SESSION['error_message'] = "Unexpected error.";
    }


    $db_connection->close();
    header("Location: ../reg.php");
    exit;

}


?>