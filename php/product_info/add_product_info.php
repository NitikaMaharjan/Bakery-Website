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
        $p_name = $_POST['p_name'];
        $p_description = $_POST['p_description'];
        $p_price = $_POST['p_price'];
        $p_category = $_POST['p_category'];

        if (isset($_FILES['p_image']) && $_FILES['p_image']['error'] == 0) {

            $targetFile = "/LocalBakehouse/images/product_image/" . basename($_FILES['p_image']['name']);

            $sql = "INSERT INTO productinfo (p_image, p_name, p_description, p_price, p_category) VALUES ('$targetFile', '$p_name' ,'$p_description', '$p_price', '$p_category')";

            if ($conn->query($sql) === TRUE) {
                $r_description = "Added a new product info";
                $a_id = $_SESSION['id'];

                $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
                $conn->query($activitySql);
                
                echo "<script>
                        alert('Product added successfully!!');
                        window.location.href = '/LocalBakehouse/php/product_info/show_product_info.php';
                      </script>";
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo "<script>
                    alert('No file uploaded or there was an error!');
                    window.history.back();
                  </script>";
        }
    }

    $conn->close();
?>
