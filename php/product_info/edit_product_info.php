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

    if (isset($_GET['p_id'])) {
        $p_id = (int)$_GET['p_id'];

        $sql2 = "SELECT * FROM productinfo WHERE p_id = '$p_id'";
        $result = $conn->query($sql2);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "<script>
                    alert('No product info found!');
                    window.history.back();
                </script>";
            exit();
        }
    } else {
        echo "Invalid request: product id not provided.";
        exit();
    }
?>

<html>
    <head>
        <title>Bakery Management</title>
        <link rel="icon" href="/LocalBakehouse/icons/logo.png" type="image/png">
        <link rel="stylesheet" href="/LocalBakehouse/css/edit_product_info.css" type="text/css">
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
                    <button id="show_bakery_image" onclick="window.location.href='/LocalBakehouse/php/bakery_image/show_bakery_image.php'"><img id="icon" src="/LocalBakehouse/icons/images_white.png"/>&nbsp;&nbsp;Bakery Images</button>
                    <button id="show_product_info" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'"><img id="icon" src="/LocalBakehouse/icons/product_black.png"/>&nbsp;&nbsp;Product Information</button>
                    <button id="settings" onclick="window.location.href='/LocalBakehouse/php/settings/settings.php'"><img id="icon" src="/LocalBakehouse/icons/settings_white.png"/>&nbsp;&nbsp;Settings</button>
                    <button id="logout" onclick="confirmit()"><img id="icon" src="/LocalBakehouse/icons/logout_white.png"/>&nbsp;&nbsp;Log out</button>
                </div>
            </div>

            <div class="contents">
                <div class="three">
                    <h1>Edit Product Information</h1>
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

                <div class="productinfo">
                    <button id="back" onclick="window.location.href='/LocalBakehouse/php/product_info/show_product_info.php'"><img id="icon" src='/LocalBakehouse/icons/back.png'/>&nbsp;&nbsp;Back</button>

                    <form action="/LocalBakehouse/php/product_info/update_product_info.php?p_id=<?php echo $p_id; ?>" method="POST" enctype="multipart/form-data">

                        <div id="form-line">
                            <label for="p_image">Product Image:</label>
                            <?php if (!empty($row['p_image'])): ?>
                                <img src="<?php echo htmlspecialchars($row['p_image']); ?>" alt="Product image" style="max-width: 150px; max-height: 150px;" />
                            <?php else: ?>
                                <p>No image available</p>
                            <?php endif; ?>
                        </div>
    
                        <input type="file" name="p_image" accept="image/*" id="p_image"/>
            
                        <div id="form-line">
                            <label for="p_name">Product Name:</label>
                            <input type="text" name="p_name" id="p_name" value="<?php echo htmlspecialchars($row['p_name']); ?>" required />
                        </div>

                        <div id="form-line">
                            <label for="p_description">Product Description:</label>
                            <textarea name="p_description" id="p_description" required><?php echo htmlspecialchars($row['p_description']); ?></textarea>
                        </div>

                        <div id="form-line">
                            <label for="p_price">Product Price:</label>
                            <input type="number" name="p_price" id="p_price" value="<?php echo htmlspecialchars($row['p_price']); ?>" required />
                        </div>

                        <div id="form-line">
                            <label for="p_category">Product Category:</label>
                            <select name="p_category" id="p_category" required>
                                <option value="whole_cake" <?php echo ($row['p_category'] == 'whole_cake') ? 'selected' : ''; ?>>Whole Cake</option>
                                <option value="cake_slice" <?php echo ($row['p_category'] == 'cake_slice') ? 'selected' : ''; ?>>Cake Slice</option>
                                <option value="cupcakes" <?php echo ($row['p_category'] == 'cupcakes') ? 'selected' : ''; ?>>Cupcakes</option>
                                <option value="cookies" <?php echo ($row['p_category'] == 'cookies') ? 'selected' : ''; ?>>Cookies</option>
                                <option value="bread" <?php echo ($row['p_category'] == 'bread') ? 'selected' : ''; ?>>Bread</option>
                                <option value="donuts" <?php echo ($row['p_category'] == 'donuts') ? 'selected' : ''; ?>>Donuts</option>
                                <option value="macaron" <?php echo ($row['p_category'] == 'macaron') ? 'selected' : ''; ?>>macaron</option>
                                <option value="pie_pastries" <?php echo ($row['p_category'] == 'pie_pastries') ? 'selected' : ''; ?>>Pie & Pastries</option>
                                <option value="others" <?php echo ($row['p_category'] == 'others') ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>

                        <input type="submit" value="Update Product Info" style="margin-left: 825px;"/>
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
