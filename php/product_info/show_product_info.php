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

    $category = isset($_GET['category']) ? $_GET['category'] : 'all_category';
    $allowed_categories = ['all_category', 'whole_cake', 'cake_slice', 'cupcakes', 'cookies', 'bread', 'donuts', 'macaron', 'pie_pastries', 'others'];

    if (!in_array($category, $allowed_categories)) {
        $category = 'all_category';
    }

    $sqlProducts = $category === 'all_category' 
        ? "SELECT * FROM productinfo ORDER BY created_at DESC" 
        : "SELECT * FROM productinfo WHERE p_category = '$category' ORDER BY created_at DESC";

    $products = $conn->query($sqlProducts);

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($products->num_rows > 0) {
            while ($product = $products->fetch_assoc()) {
                echo "<div class='product-item'>
                          <div class='buttons'><button id='edit-delete-btn' onclick='edit_product(".htmlspecialchars($product['p_id']).")' title='Edit'>
                              <img id='icon' src='/LocalBakehouse/icons/edit_black.png' />
                          </button>&nbsp;
                          <button id='edit-delete-btn' onclick='delete_product(".htmlspecialchars($product['p_id']).")' title='Delete'>
                              <img id='icon' src='/LocalBakehouse/icons/delete_black.png' />
                          </button></div>
                          <img src='" . htmlspecialchars($product['p_image']) . "' alt='Product Image'>
                          <p id='p-name'>" . htmlspecialchars($product['p_name']) . "</p>
                          <p id='p-description'>" . htmlspecialchars($product['p_description']) . "</p>
                          <p>Rs. " . htmlspecialchars($product['p_price']) . "</p>
                      </div>";
            }
        } else {
            echo "<p id='no-info'>Products has not been added yet!</p>";
        }
        exit;
    }    
?>

<html>
    <head>
        <title>Bakery Management</title>
        <link rel="icon" href="/LocalBakehouse/icons/logo.png" type="image/png">
        <link rel="stylesheet" href="/LocalBakehouse/css/show_product_info.css" type="text/css">
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
                    <h1>Product Information</h1>
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
                    <button id="add_product_info_btn" onclick="window.location.href='/LocalBakehouse/php/product_info/add_product_info1.php'">Add product</button>

                    <div class="cat-buttons">
                        <?php
                            foreach ($allowed_categories as $cat) {
                                $label = ucwords(str_replace('_', ' ', $cat));
                                echo "<button onclick=\"filterCategory('$cat', event)\">$label</button>";
                            }
                        ?>
                    </div>

                    <div id="product-list">
                    <?php if($products->num_rows > 0) { 
                        while ($product = $products->fetch_assoc()) { ?>
                            <div class="product-item">
                                <div class="buttons">
                                    <button id="edit-delete-btn" onclick="edit_product(<?php echo htmlspecialchars($product['p_id']); ?>)" title="Edit"><img id="icon" src="/LocalBakehouse/icons/edit_black.png"/></button>&nbsp;
                                    <button id="edit-delete-btn" onclick="delete_product(<?php echo htmlspecialchars($product['p_id']); ?>)" title="Delete"><img id="icon"src="/LocalBakehouse/icons/delete_black.png"/></button>
                                </div> 
                                <img src="<?php echo htmlspecialchars($product['p_image']); ?>" alt="Product Image" >
                                <p id="p-name"><?php echo htmlspecialchars($product['p_name']); ?></p>
                                <p id="p-description"><?php echo htmlspecialchars($product['p_description']); ?></p>
                                <p>Rs. <?php echo htmlspecialchars($product['p_price']); ?></p>
                            </div>
                    <?php } ?>
                    <?php 
                    } else {
                        echo "<p id='no-info'>Products has not been added yet!</p>";
                    } ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        function filterCategory(category, event) {
            const buttons = document.querySelectorAll('.cat-buttons button');
            buttons.forEach(button => button.classList.remove('active')); 
            event.target.classList.add('active');
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/LocalBakehouse/php/product_info/show_product_info.php?category=' + category, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('product-list').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentCategory = '<?php echo $category; ?>';
            if (currentCategory === 'all_category') {
                const allCategoryButton = document.querySelector('.cat-buttons button');
                allCategoryButton.classList.add('active');
            }
        });

        function delete_product(p_id) {
            if (confirm("Are you sure you want to delete this product?")) {
                window.location.href = "/LocalBakehouse/php/product_info/delete_product_info.php?p_id=" + p_id;
            }
        }

        function edit_product(p_id) {
            window.location.href = "/LocalBakehouse/php/product_info/edit_product_info.php?p_id=" + p_id;
        }
    </script>
</html>

<?php
    $conn->close();
?>
