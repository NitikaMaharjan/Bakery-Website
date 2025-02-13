<?php
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="localbakehouse";

    $conn=new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }

    $sql="CREATE TABLE admin (
            a_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            a_username VARCHAR(50) NOT NULL UNIQUE,
            a_email VARCHAR(255) NOT NULL UNIQUE,
            a_profile_pic VARCHAR(255) DEFAULT NULL,  
            a_password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if($conn->query($sql)===true){
        echo "admin table created successfully";
    }else{
        echo "error creating table ".$conn->error;
    }

    $conn->close();
?>