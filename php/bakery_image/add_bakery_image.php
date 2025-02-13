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
        $i_description = $_POST['i_description'];

        if (isset($_FILES['i_image']) && $_FILES['i_image']['error'] == 0) {

            $targetFile = "/LocalBakehouse/images/bakery_image/" . basename($_FILES['i_image']['name']);

            $sql = "INSERT INTO bakeryimage (i_image, i_description) VALUES ('$targetFile', '$i_description')";

            if ($conn->query($sql) === TRUE) {
                $r_description = "Added a new bakery image";
                $a_id = $_SESSION['id'];

                $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
                $conn->query($activitySql);

                echo "<script>
                        alert('Bakery image added successfully!!');
                        window.location.href = '/LocalBakehouse/php/bakery_image/show_bakery_image.php';
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
