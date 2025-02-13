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

    if (isset($_GET['p_id'])) {
        $p_id = (int)$_GET['p_id'];
  
        $sql = "DELETE FROM productinfo WHERE p_id = $p_id";
        if ($conn->query($sql) === TRUE) {
            $r_description = "Deleted a product info";
            $a_id = $_SESSION['id'];

            $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
            $conn->query($activitySql);
            
            echo "<script>
                    alert('Product deleted successfully!!');
                    window.location.href = '/LocalBakehouse/php/product_info/show_product_info.php';
                  </script>";
            exit();
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    } else {
        echo "Invalid request: product id not provided.";
    }

    $conn->close();
?>
