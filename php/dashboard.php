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

    $sqlBakeryInfo = "SELECT b_name, b_logo, created_at, updated_at FROM bakeryinfo WHERE b_id = 1";
    $bakeryInfoResult = $conn->query($sqlBakeryInfo);
    $bakeryInfo = $bakeryInfoResult->fetch_assoc();

    $sqlBakeryImages = "SELECT COUNT(*) AS total_images, MAX(i_added_date) AS last_added_date FROM bakeryimage";
    $bakeryImagesResult = $conn->query($sqlBakeryImages);
    $bakeryImages = $bakeryImagesResult->fetch_assoc();

    $sqlProducts = "SELECT COUNT(*) AS total_products, MAX(created_at) AS last_added_date FROM productinfo";
    $productResult = $conn->query($sqlProducts);
    $products = $productResult->fetch_assoc();

    $sqlRecentActivities = "SELECT r_description, r_date FROM recentactivity WHERE a_id = $a_id ORDER BY r_date DESC LIMIT 6";
    $recentActivitiesResult = $conn->query($sqlRecentActivities);

    $user = "SELECT a_profile_pic, a_username, a_email FROM admin WHERE a_id = $a_id";
    $userInfo = $conn->query($user);
?>

<html>
    <head>
        <title>Bakery Management</title>
        <link rel="icon" href="/LocalBakehouse/icons/logo.png" type="image/png">
        <link rel="stylesheet" href="/LocalBakehouse/css/dashboard.css" type="text/css">
    </head>
    <body>
        <div class="page">
            <div class="side-bar">
                <div class="one">
                    <p id="b-name">Bakery Management</p>
                </div>
                <div class="two">
                    <button id="dashboard" onclick="window.location.href='/LocalBakehouse/php/dashboard.php'"><img id="icon" src="/LocalBakehouse/icons/dashboard_black.png"/>&nbsp;&nbsp;Dashboard</button>
                    <button id="show_bakery_info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/show_bakery_info.php'"><img id="icon" src="/LocalBakehouse/icons/info_white.png"/>&nbsp;&nbsp;Bakery Information</button>
                    <button id="show_bakery_image" onclick="window.location.href='/LocalBakehouse/php/bakery_image/show_bakery_image.php'"><img id="icon" src="/LocalBakehouse/icons/images_white.png"/>&nbsp;&nbsp;Bakery Images</button>
                    <button id="show_product_info" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'"><img id="icon" src="/LocalBakehouse/icons/product_white.png"/>&nbsp;&nbsp;Product Information</button>
                    <button id="settings" onclick="window.location.href='/LocalBakehouse/php/settings/settings.php'"><img id="icon" src="/LocalBakehouse/icons/settings_white.png"/>&nbsp;&nbsp;Settings</button>
                    <button id="logout" onclick="confirmit()"><img id="icon" src="/LocalBakehouse/icons/logout_white.png"/>&nbsp;&nbsp;Log out</button>
                </div>
            </div>

            <div class="contents">
                <div class="three">
                    <h1>Dashboard</h1>
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
                <div class="four-five">
                    <div class="four">
                        <div class="bakery-info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/show_bakery_info.php'">
                            <h3>Bakery info</h3>
                            <?php
                                if ($bakeryInfo) {
                                    if ($bakeryInfo['created_at'] == $bakeryInfo['updated_at']) {
                                        echo "<p>Last added on " . $bakeryInfo['created_at'] . "</p>";
                                    } else {
                                        echo "<p>Last edited on " . $bakeryInfo['updated_at'] . "</p>";
                                    }
                                }else{
                                    echo "<p>Bakery info has not been added yet!</p>";
                                }
                            ?>
                        </div>


                        <div class="bakery-images" onclick="window.location.href='/LocalBakehouse/php/bakery_image/show_bakery_image.php'">
                            <h3>Bakery images</h3>
                            <?php
                                echo "<p>Total Images: " . ($bakeryImages['total_images'] ?? 0) . "</p>";
                                if ($bakeryImages['last_added_date']) {
                                    echo "<p>Last image added on " . $bakeryImages['last_added_date'] . "</p>";
                                }
                            ?>
                        </div>

                        <div class="product-info" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'">
                            <h3>Products info</h3>
                            <?php
                                echo "<p>Total Products: " . ($products['total_products'] ?? 0) . "</p>";
                                if ($products['last_added_date']) {
                                    echo "<p>Last product added on " . $products['last_added_date'] . "</p>";
                                }
                            ?>
                        </div>
                    </div>
                    
                    <div class="five">
                        <h3>Recent Activities</h3>
                        <?php
                            if ($recentActivitiesResult->num_rows > 0) {
                                while ($row = $recentActivitiesResult->fetch_assoc()) {
                                    echo "<p><span id='activity'>" . $row['r_description'] . "</span> - " . $row['r_date'] . "</p>";
                                }
                                echo "<button id='view-all-activity-btn' onclick=\"window.location.href='/LocalBakehouse/php/all_activity.php'\">View all activity&nbsp;&nbsp;<img src='/LocalBakehouse/icons/arrow.png' height='20px' width='20px'/></button>";
                            } else {
                                echo "<p>No recent activity found!</p>";
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
    </script>
</html>

<?php
    $conn->close();
?>
