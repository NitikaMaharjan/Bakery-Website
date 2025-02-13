<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE recentactivity (
            r_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            a_id INT(6) UNSIGNED,
            r_description TEXT NOT NULL,
            r_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (a_id) REFERENCES admin(a_id)
        )";

    if ($conn->query($sql) === true) {
        echo "Recent activity table created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
?>
