<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $a_username = $_POST['a_username'];
        $a_password = $_POST['a_password'];

        if (empty($a_username) || empty($a_password)) {
            echo "<script>
                    alert ('All fields are required!!');
                    window.location.href = '/LocalBakehouse/html/login.html';
                  </script>";
            exit();
        } else {
            $sql = "SELECT a_id, a_username, a_password FROM admin WHERE a_username='$a_username' AND a_password='$a_password'";
            $result = $conn->query($sql);

            if ($result->num_rows==1) {
                $row = $result->fetch_assoc();

                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $row['a_username'];
                $_SESSION['id'] = $row['a_id'];

                echo "<script>
                    alert('Log in successful!');
                    window.location.href = '/LocalBakehouse/php/dashboard.php';
                  </script>";
                exit();
            }else{
                echo "<script>
                        alert ('Incorrect username and password, Please try again!!');
                        window.location.href = '/LocalBakehouse/html/login.html';
                      </script>";
                exit();
            }
        }
        
    }
    $conn->close();
?>