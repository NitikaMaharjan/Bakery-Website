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

        $current_password = $_POST['currentpassword'];
        $new_password = $_POST['newpassword'];
        $confirm_password = $_POST['confirmpassword'];

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo "<script>
                    alert('All fields are required!');
                    window.location.href = '/LocalBakehouse/php/settings/change_password.php';
                </script>";
            exit();
        } else {
            $sql = "SELECT a_password, a_username FROM admin WHERE a_id='$a_id'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $stored_password = $row['a_password'];
                $a_username = $row['a_username'];

                if ($current_password == $stored_password) {

                    if (strlen($new_password) > 10) {
                        echo "<script>
                                alert('New password must be less than 10 characters. Please try again!!');
                                window.location.href = '/LocalBakehouse/php/settings/change_password.php';
                            </script>";
                        exit();
                    }

                    if (preg_match('/\s/', $new_password)) {
                        echo "<script>
                                alert('New password must not contain spaces. Please try again!!');
                                window.location.href = '/LocalBakehouse/php/settings/change_password.php';
                            </script>";
                        exit();
                    }

                    if (strpos($new_password, $a_username) !== false) {
                        echo "<script>
                                alert('New password must not contain your username. Please try again!!');
                                window.location.href = '/LocalBakehouse/php/settings/change_password.php';
                            </script>";
                        exit();
                    }

                    if ($new_password != $confirm_password) {
                        echo "<script>
                                alert('New password and confirm password must match. Please try again!!');
                                window.location.href = '/LocalBakehouse/php/settings/change_password.php';
                            </script>";
                        exit();
                    }

                    $sql2 = "UPDATE admin SET a_password='$new_password' WHERE a_id='$a_id'";

                    if ($conn->query($sql2) === TRUE) {
                        $r_description = "Changed password";
                        $a_id = $_SESSION['id'];

                        $activitySql = "INSERT INTO recentactivity (r_description, a_id) VALUES ('$r_description', $a_id)";
                        $conn->query($activitySql);

                        echo "<script>
                                alert('Password updated successfully!');
                                window.location.href = '/LocalBakehouse/php/settings/settings.php';
                            </script>";
                        exit();
                    } else {
                        echo "Error updating password: " . $conn->error;
                    }

                } else {
                    echo "<script>
                            alert('Incorrect current password. Please try again!!');
                            window.location.href = '/LocalBakehouse/php/settings/change_password1.php';
                        </script>";
                    exit();
                }
            }
        }
    }
    $conn->close();
?>