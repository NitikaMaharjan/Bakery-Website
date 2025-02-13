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
?>

<html>
    <head>
        <title>Bakery Management</title>
        <link rel="icon" href="/LocalBakehouse/icons/logo.png" type="image/png">
        <link rel="stylesheet" href="/LocalBakehouse/css/show_bakery_info.css" type="text/css">
    </head>
    <body>
        <div class="page">
            <div class="side-bar">
                <div class="one">
                    <p id="b-name">Bakery Management</p>
                </div>
                <div class="two">
                    <button id="dashboard" onclick="window.location.href='/LocalBakehouse/php/dashboard.php'"><img id="icon" src="/LocalBakehouse/icons/dashboard_white.png"/>&nbsp;&nbsp;Dashboard</button>
                    <button id="show_bakery_info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/show_bakery_info.php'"><img id="icon" src="/LocalBakehouse/icons/info_black.png"/>&nbsp;&nbsp;Bakery Information</button>
                    <button id="show_bakery_image" onclick="window.location.href='/LocalBakehouse/php/bakery_image/show_bakery_image.php'"><img id="icon" src="/LocalBakehouse/icons/images_white.png"/>&nbsp;&nbsp;Bakery Images</button>
                    <button id="show_product_info" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'"><img id="icon" src="/LocalBakehouse/icons/product_white.png"/>&nbsp;&nbsp;Product Information</button>
                    <button id="settings" onclick="window.location.href='/LocalBakehouse/php/settings/settings.php'"><img id="icon" src="/LocalBakehouse/icons/settings_white.png"/>&nbsp;&nbsp;Settings</button>
                    <button id="logout" onclick="confirmit()"><img id="icon" src="/LocalBakehouse/icons/logout_white.png"/>&nbsp;&nbsp;Log out</button>
                </div>
            </div>

            <div class="contents">
                <div class="three">
                    <h1>Bakery Information</h1>
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

                <div class="bakeryinfo">
                    <?php if ($bakeryInfo): ?>
                        <button id="edit_bakery_info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/edit_bakery_info.php'"><img id="icon"src="/LocalBakehouse/icons/edit_white.png"/>&nbsp;&nbsp;Edit</button>
                        
                        <div class="form">
                            <div class="form-line">
                                <p>
                                    <div id="title">Bakery Name: </div>
                                    <div id="text"><?php echo htmlspecialchars($bakeryInfo['b_name']); ?></div>
                                </p>
                            </div>

                            <div class="form-line">
                                <p>
                                    <div id="title">Bakery Logo: </div>
                                    <div id="text2">
                                        <?php if (!empty($bakeryInfo['b_logo'])): ?>
                                            <img src="<?php echo htmlspecialchars($bakeryInfo['b_logo']); ?>" alt="Bakery Logo" style="max-width: 150px; max-height: 150px;">
                                        <?php else: ?>
                                            No logo available
                                        <?php endif; ?>
                                    </div>
                                </p>
                            </div>
                            
                            <div class="form-line">
                                <p>
                                    <div id="title">Bakery Description: </div>
                                    <div id="text" style="height:200px; line-height:1.5;"><?php echo htmlspecialchars($bakeryInfo['b_description']); ?></div>
                                </p>
                            </div>

                            <div class="form-line">
                                <p>
                                    <div id="title">Bakery Address:</div>
                                    <div id="text"><?php echo htmlspecialchars($bakeryInfo['b_address']); ?></div>
                                </p>
                            </div>
                            
                            <div class="form-line">
                                <p>
                                    <div id="title">Bakery Contact Number:</div>
                                    <div id="text"><?php echo htmlspecialchars($bakeryInfo['b_contact_number']); ?></div>
                                </p>
                            </div>
                            
                            <div class="form-line" style="margin-bottom:0px;">
                                <p>
                                    <div id="title">Bakery Email:</div>
                                    <div id="text"><?php echo htmlspecialchars($bakeryInfo['b_email']); ?></div>
                                </p>
                            </div>
                        </div>
                            
                    <?php else: ?>
                        <button id="add_bakery_info" onclick="window.location.href='/LocalBakehouse/php/bakery_info/add_bakery_info1.php'" style="margin-left:870px;">Add Bakery Info</button>
                        <p id="no-info">Bakery information has not been added yet!</p>
                    <?php endif; ?>
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
