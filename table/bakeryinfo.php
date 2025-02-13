<?php
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="localbakehouse";

    $conn=new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }

    $sql="CREATE TABLE bakeryinfo (
            b_id INT PRIMARY KEY DEFAULT 1,              
            b_name VARCHAR(255) NOT NULL,               
            b_logo VARCHAR(255) DEFAULT NULL,           
            b_description TEXT NOT NULL,                
            b_address VARCHAR(255) NOT NULL,            
            b_contact_number VARCHAR(15) NOT NULL,        
            b_email VARCHAR(255) NOT NULL,          
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

    if($conn->query($sql)===true){
        echo "bakery info table created successfully";
    }else{
        echo "error creating table ".$conn->error;
    }

    $conn->close();
?>