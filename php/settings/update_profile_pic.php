<?php
    session_start();
    if (!(isset($_SESSION['username']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        header("Location: /LocalBakehouse/html/login.html");
        exit();
    }

    $a_id=$_SESSION["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES['a_profile_pic']) && $_FILES['a_profile_pic']['error'] == 0) {
            $targetDir = "/LocalBakehouse/images/a_profile_pic/"; 
            $targetFile = $targetDir . basename($_FILES['a_profile_pic']['name']);

            $sql = "UPDATE admin SET a_profile_pic='$targetFile' WHERE a_id=$a_id";
        }
        if ($conn->query($sql) === TRUE) {
            $r_description = "Updated profile picture";
            $a_id = $_SESSION['id'];

            $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
            $conn->query($activitySql);
            
            echo "<script>
                    alert('Profile picture updated successfully!!');
                    window.location.href = '/LocalBakehouse/php/settings/settings.php';
                </script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
?>
