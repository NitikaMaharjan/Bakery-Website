<?php
    session_start();
    if (!(isset($_SESSION['username']) && isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        header("Location: /LocalBakehouse/html/login.html");
        exit();
    }
    $a_id = $_SESSION["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user = "SELECT a_profile_pic, a_username, a_email FROM admin WHERE a_id = $a_id";
    $userInfo = $conn->query($user);

    $sql = "SELECT * FROM bakeryinfo WHERE b_id = 1";
    $bakeryInfo = $conn->query($sql)->fetch_assoc();

    $sql2 = "SELECT * FROM bakeryimage";
    $result = $conn->query($sql2);
?>

<html>
    <head>
        <title>Bakery Management</title>
        <link rel="icon" href="/LocalBakehouse/icons/logo.png" type="image/png">
        <link rel="stylesheet" href="/LocalBakehouse/css/show_bakery_image.css" type="text/css">
    </head>
    <body>
        <div class="page">
            <div class="side-bar">
                <div class="one">
                    <p id="b-name">Bakery Management</p>
                </div>
                <div class="two">
                    <button id="dashboard" onclick="window.location.href='/LocalBakehouse/php/dashboard.php'"><img id="icon" src="/LocalBakehouse/icons/dashboard_white.png"/>&nbsp;&nbsp;Dashboard</button>
                    <button id="show_bakery_info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/show_bakery_info.php'"><img id="icon" src="/LocalBakehouse/icons/info_white.png"/>&nbsp;&nbsp;Bakery Information</button>
                    <button id="show_bakery_image" onclick="window.location.href='/LocalBakehouse/php/bakery_image/show_bakery_image.php'"><img id="icon" src="/LocalBakehouse/icons/images_black.png"/>&nbsp;&nbsp;Bakery Images</button>
                    <button id="show_product_info" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'"><img id="icon" src="/LocalBakehouse/icons/product_white.png"/>&nbsp;&nbsp;Product Information</button>
                    <button id="settings" onclick="window.location.href='/LocalBakehouse/php/settings/settings.php'"><img id="icon" src="/LocalBakehouse/icons/settings_white.png"/>&nbsp;&nbsp;Settings</button>
                    <button id="logout" onclick="confirmit()"><img id="icon" src="/LocalBakehouse/icons/logout_white.png"/>&nbsp;&nbsp;Log out</button>
                </div>
            </div>

            <div class="contents">
                <div class="three">
                    <h1>Bakery Images</h1>
                    <div class="top-right">
                        <button id="home" onclick="window.open('/LocalBakehouse/website/home.php', '_blank')">Go to Website&nbsp;<img src="/LocalBakehouse/icons/go_arrow.png"/></button>&nbsp;&nbsp;&nbsp;
                        <div class="profile" onclick="window.location.href='/LocalBakehouse/php/settings/settings.php'">
                            <?php
                            if ($userInfo->num_rows == 1) {
                                $userinfo = $userInfo->fetch_assoc();
                                $userinfoUrl = $userinfo['a_profile_pic'] ? htmlspecialchars($userinfo['a_profile_pic']) : '/LocalBakehouse/default_profile_picture.jpg';
                                echo "<div><img id='profile-pic' src='$userinfoUrl' title='You'/></div>&nbsp;&nbsp;&nbsp;";
                            }
                            echo "<div><h3>".htmlspecialchars($userinfo['a_username'])."</h3>";
                            echo "<p>".htmlspecialchars($userinfo['a_email'])."</p></div>";
                            ?>
                        </div>
                    </div>
                </div>
                <div class="bakeryimage">
                    <button id="add_bakery_image" onclick="window.location.href='/LocalBakehouse/php/bakery_image/add_bakery_image1.php'" style="margin-left:830px;">Add Bakery Image</button>
                    <div class="image-box">
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<p><div class='image'>";
                                        echo "<div class='buttons'><button onclick='edit_bakery_image_description(" . $row['i_id'] . ")' title='Edit'><img id='icon'src='/LocalBakehouse/icons/edit_black.png'/></button>&nbsp;";
                                        echo "<button onclick='delete_bakery_image(" . $row['i_id'] . ")' title='Delete'><img id='icon'src='/LocalBakehouse/icons/delete_black.png'/></button></div>";
                                    
                                        echo "<img src='" . htmlspecialchars($row['i_image']) . "' alt='Bakery Image' id='b_image'>";
                                    
                                        echo "<p>".htmlspecialchars($row['i_description'])."</p>";
                                    echo "</p></div>";
                                }
                            }else{
                                echo "<p id='no-info'>Bakery image has not been added yet!</p>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        function confirmit() {
            var ans = confirm("Are you sure you want to log out?");
            if (ans) {
                window.location.href = "/LocalBakehouse/php/logout.php";
            }
        }
        
        function edit_bakery_image_description(i_id) {
                window.location.href = "/LocalBakehouse/php/bakery_image/edit_bakery_image_description.php?i_id=" + i_id;
        }

        function delete_bakery_image(i_id) {
            var ans = confirm("Are you sure you want to delete this image?");
            if (ans == true) {
                window.location.href = "/LocalBakehouse/php/bakery_image/delete_bakery_image.php?i_id=" + i_id;
            }
        }
    </script>
</html>

<?php
    $conn->close();
?>
