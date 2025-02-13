<?php
    session_start();
    if (!(isset($_SESSION['username']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        header("Location: /LocalBakehouse/html/login.html");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $b_name = $_POST['b_name'];
        $b_description = $_POST['b_description'];
        $b_address = $_POST['b_address'];
        $b_contact_number = $_POST['b_contact_number'];
        $b_email = $_POST['b_email'];

        if (isset($_FILES['b_logo']) && $_FILES['b_logo']['error'] == 0){
            $targetDir = "/LocalBakehouse/images/bakery_logo/"; 
            $targetFile = $targetDir . basename($_FILES['b_logo']['name']);

            $sql = "INSERT INTO bakeryinfo (b_name, b_logo, b_description, b_address, b_contact_number, b_email) 
                    VALUES ('$b_name', '$targetFile', '$b_description', '$b_address', '$b_contact_number', '$b_email')";

            if ($conn->query($sql) === TRUE) {
                $r_description = "Added bakery info";
                $a_id = $_SESSION['id'];

                $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
                $conn->query($activitySql);

                echo "<script>
                        alert('Bakery info added successfully!');
                        window.location.href = '/LocalBakehouse/php/bakery_info/show_bakery_info.php';
                    </script>";
                exit();
            } else {
                echo "Error adding bakery info".$conn->error;
            }
        }
    }
    $conn->close();
?>
