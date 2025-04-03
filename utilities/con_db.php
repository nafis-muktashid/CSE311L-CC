<?php

    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "connectcore";


    $db_connection = new mysqli($db_host, $db_user, $db_password, $db_name);

    if($db_connection -> connect_error){
        die("Connection Failed: ".$db_connection->connect_error);
        echo "Code issue";
    }else{
        echo "Connection Okay";
    }




?>