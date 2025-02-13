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
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $p_name = $_POST['p_name'];
        $p_description = $_POST['p_description'];
        $p_price = $_POST['p_price'];
        $p_category = $_POST['p_category'];

        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == 0) {
            $targetDir = "/LocalBakehouse/images/product_image/"; 
            $targetFile = $targetDir . basename($_FILES['p_image']['name']);

            $sql = "UPDATE productinfo SET p_image='$targetFile', p_name='$p_name', p_description='$p_description', p_price='$p_price', p_category='$p_category' WHERE p_id='$p_id'";
        } else {
            $sql = "UPDATE productinfo SET p_name='$p_name', p_description='$p_description', p_price='$p_price', p_category='$p_category' WHERE p_id='$p_id'";
        }

        if ($conn->query($sql) === TRUE) {
            $r_description = "Updated a product info";
            $a_id = $_SESSION['id'];

            $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
            $conn->query($activitySql);
            
            echo "<script>
                    alert('Product info updated successfully!!');
                    window.location.href = '/LocalBakehouse/php/product_info/show_product_info.php';
                </script>";
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $conn->close();
?>
