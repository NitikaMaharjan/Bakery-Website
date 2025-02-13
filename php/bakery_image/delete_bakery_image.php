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

    if (isset($_GET['i_id'])) {
        $i_id = (int)$_GET['i_id'];
  
        $sql = "DELETE FROM bakeryimage WHERE i_id = $i_id";
        if ($conn->query($sql) === TRUE) {
            $r_description = "Deleted a bakery image";
            $a_id = $_SESSION['id'];

            $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
            $conn->query($activitySql);
            
            echo "<script>
                    alert('Bakery image deleted successfully!!');
                    window.location.href = '/LocalBakehouse/php/bakery_image/show_bakery_image.php';
                  </script>";
            exit();
        } else {
            echo "Error deleting bakery image: " . $conn->error;
        }
    } else {
        echo "Invalid request: Image id not provided.";
    }

    $conn->close();
?>
