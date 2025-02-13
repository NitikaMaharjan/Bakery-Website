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
        <link rel="stylesheet" href="/LocalBakehouse/css/edit_bakery_info.css" type="text/css">
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
                    <h1>Edit Bakery Information</h1>
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
                    <button id="back" onclick="window.location.href='/LocalBakehouse/php/bakery_info/show_bakery_info.php'"><img id="icon" src='/LocalBakehouse/icons/back.png'/>&nbsp;&nbsp;Back</button>

                    <form action="/LocalBakehouse/php/bakery_info/update_bakery_info.php" method="POST" enctype="multipart/form-data">
                        <div id="form-line">
                            <label for="b_name">Bakery Name:</label>
                            <input type="text" name="b_name" id="b_name" value="<?php echo htmlspecialchars($bakeryInfo['b_name']); ?>" required />
                        </div>
                        
                        <div id="form-line">
                            <label for="b_logo">Bakery Logo:</label>
                            <?php if (!empty($bakeryInfo['b_logo'])): ?>
                                <img src="<?php echo htmlspecialchars($bakeryInfo['b_logo']); ?>" alt="Bakery Logo" style="max-width: 150px; max-height: 150px;" />
                            <?php else: ?>
                                <p>No logo available</p>
                            <?php endif; ?>
                        </div>
                        
                        <input type="file" name="b_logo" accept="image/*" id="b_logo" />
                        
                        <div id="form-line">
                            <label for="b_description">Bakery Description:</label>
                            <textarea name="b_description" id="b_description" required><?php echo htmlspecialchars($bakeryInfo['b_description']); ?></textarea>
                        </div>

                        <div id="form-line">
                            <label for="b_address">Bakery Address:</label>
                            <input type="text" name="b_address" id="b_address" value="<?php echo htmlspecialchars($bakeryInfo['b_address']); ?>" required />
                        </div>

                        <div id="form-line">
                            <label for="b_contact_number">Bakery Contact Number:</label>
                            <input type="text" name="b_contact_number" id="b_contact_number" value="<?php echo htmlspecialchars($bakeryInfo['b_contact_number']); ?>" required />
                        </div>

                        <div id="form-line">
                            <label for="b_email">Bakery Email:</label>
                            <input type="email" name="b_email" id="b_email" value="<?php echo htmlspecialchars($bakeryInfo['b_email']); ?>" required />
                        </div>

                        <input type="submit" value="Update Bakery Info" style="margin-left: 835px;"/>
                    </form>
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
