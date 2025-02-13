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
        $b_name = $conn->real_escape_string($_POST['b_name']);
        $b_description = $conn->real_escape_string($_POST['b_description']);
        $b_address = $conn->real_escape_string($_POST['b_address']);
        $b_contact_number = $conn->real_escape_string($_POST['b_contact_number']);
        $b_email = $conn->real_escape_string($_POST['b_email']);

        if (isset($_FILES['b_logo']) && $_FILES['b_logo']['error'] == 0) {
            $targetDir = "/LocalBakehouse/images/bakery_logo/";
            $targetFile = $targetDir . basename($_FILES['b_logo']['name']);

            $sql = "UPDATE bakeryinfo SET b_logo='$targetFile', b_name='$b_name', b_description='$b_description', b_address='$b_address', b_contact_number='$b_contact_number', b_email='$b_email' WHERE b_id=1";
        } else {
            $sql = "UPDATE bakeryinfo SET b_name='$b_name', b_description='$b_description', b_address='$b_address', b_contact_number='$b_contact_number', b_email='$b_email' WHERE b_id=1";
        }

        if ($conn->query($sql) === TRUE) {
            $r_description = "Updated bakery info";
            $a_id = $_SESSION['id'];

            $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
            $conn->query($activitySql);
            
            echo "<script>
                    alert('Bakery info updated successfully!!');
                    window.location.href = '/LocalBakehouse/php/bakery_info/show_bakery_info.php';
                </script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
?>
