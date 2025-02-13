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

    $a_id = $_SESSION['id'];

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT a_profile_pic FROM admin WHERE a_id=$a_id";
    $result=$conn->query($sql);
    
    if($result->num_rows==1){
        while($row=$result->fetch_assoc()){
            if($row['a_profile_pic']==NULL){
                echo "<script>
                        alert('You have not added any profile picture, cannot perform deletion!!');
                        window.location.href = '/LocalBakehouse/php/settings/settings.php';
                      </script>";
                exit();
            }else{
                $sql2 = "UPDATE admin SET a_profile_pic=NULL WHERE a_id=$a_id ";
                if ($conn->query($sql2) === TRUE) {
                    $r_description = "Deleted profile picture";
                    $a_id = $_SESSION['id'];

                    $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
                    $conn->query($activitySql);

                    echo "<script>
                            alert('Profile picture deleted successfully!!');
                            window.location.href = '/LocalBakehouse/php/settings/settings.php';
                            </script>";
                    exit();
                } else {
                    echo "Error deleting profile picture: " . $conn->error;
                }
            }
        }
    }

    $conn->close();
?>
