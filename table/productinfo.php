<?php
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="localbakehouse";

    $conn=new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }

    $sql="CREATE TABLE productinfo (
            p_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            p_name VARCHAR(255) NOT NULL,
            p_description TEXT NOT NULL,
            p_image VARCHAR(255) NOT NULL, 
            p_price INT NOT NULL,
            p_category ENUM('whole_cake','cake_slice', 'cupcakes', 'cookies', 'bread', 'donuts', 'macaron',  'pie_pastries', 'others') NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if($conn->query($sql)===true){
        echo "product info table created successfully";
    }else{
        echo "error creating table ".$conn->error;
    }

    $conn->close();
?>