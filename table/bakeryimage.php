<?php
    $servername="localhost";
    $username="root";
    $password="";
    $dbname="localbakehouse";

    $conn=new mysqli($servername, $username, $password, $dbname);
    if($conn->connect_error){
        die("Connection failed".$conn->connect_error);
    }

    $sql="CREATE TABLE bakeryimage (
            i_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            i_image VARCHAR(255) NOT NULL,
            i_description TEXT,
            i_added_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

    if($conn->query($sql)===true){
        echo "bakery image table created successfully";
    }else{
        echo "error creating table ".$conn->error;
    }

    $conn->close();
?>