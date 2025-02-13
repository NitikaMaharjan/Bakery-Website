<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlBakeryInfo = "SELECT b_name, b_logo, b_description, b_address, b_contact_number, b_email FROM bakeryinfo WHERE b_id = 1";
    $bakeryInfo = $conn->query($sqlBakeryInfo)->fetch_assoc();

    $sqlBakeryImage = "SELECT i_image FROM bakeryimage LIMIT 2";
    $bakeryImage = $conn->query($sqlBakeryImage);

    $category = isset($_GET['category']) ? $_GET['category'] : 'all_category';
    $allowed_categories = ['all_category', 'whole_cake', 'cake_slice', 'cupcakes', 'cookies', 'bread', 'donuts', 'macaron', 'pie_pastries', 'others'];

    if (!in_array($category, $allowed_categories)) {
        $category = 'all_category';
    }

    $sqlProducts = $category === 'all_category' 
        ? "SELECT * FROM productinfo ORDER BY created_at DESC LIMIT 10" 
        : "SELECT * FROM productinfo WHERE p_category = '$category' ORDER BY created_at DESC LIMIT 10";

    $products = $conn->query($sqlProducts);
    
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($products->num_rows > 0) {
            while ($product = $products->fetch_assoc()) {
                echo "<div class='product-item' onclick='openModal(\"" . htmlspecialchars($product['p_image']) . "\", \"" . htmlspecialchars($product['p_description']) . "\", \"" . htmlspecialchars($product['p_name']) . "\", \"" . htmlspecialchars($product['p_price']) . "\")'>
                            <img src='" . htmlspecialchars($product['p_image']) . "' alt='Product Image'>
                            <p id='p-name'>" . htmlspecialchars($product['p_name']) . "</p>
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
        <?php
            if ($bakeryInfo!=null) {
                echo "<title>".htmlspecialchars($bakeryInfo['b_name'])."</title>";
                echo "<link rel='icon' href='" . htmlspecialchars($bakeryInfo['b_logo']) . "' type='image/png'>";
            } else {
                echo "<title>Bakery</title>";
                echo "<link rel='icon' href='/LocalBakehouse/icons/logo.png' type='image/png'>";
            }
        ?>
        <link rel="stylesheet" href="/LocalBakehouse/css/home.css" type="text/css">
    </head>
    <body>
        <div class="navbar">
            <div class="logo-nav">
                <div class="logo">
                    <?php
                        if ($bakeryInfo!=null) {
                            echo "<img src='".htmlspecialchars($bakeryInfo['b_logo'])."' alt='Bakery Logo'>";
                            echo "<p>".htmlspecialchars($bakeryInfo['b_name'])."</p>";
                        } else {
                            echo "<img src='/LocalBakehouse/icons/logo2.png' alt='Bakery Logo' style='height:40px; width:50px;'>";
                            echo "<p>Bakery</p>";
                        }
                    ?>
                </div>
                <div class="nav-links">
                    <a class="home-link" href="/LocalBakehouse/website/home.php">Home</a>
                    <a class="product-link" href="/LocalBakehouse/website/product.php">Products</a>
                    <a class="gallery-link" href="/LocalBakehouse/website/gallery.php">Gallery</a>
                </div>
            </div>
            <div class="social">
                <a id="ig" href="https://instagram.com" target="_blank"><img src="/LocalBakehouse/icons/ig_white_unfilled.png" alt="Instagram" height="30px" width="30px"></a>
                <a id="fb" href="https://facebook.com" target="_blank"><img src="/LocalBakehouse/icons/fb_white.png" alt="Facebook" height="25px" width="25px"></a>
            </div>
        </div>

        <div class="contents">
            <h1 class="animated-heading">Crafting Sweet Moments with Freshly Baked Joy<br/>Just for You.</h1>

            <div class="frame1">
                <button class="view-products-btn" onclick="scrollToSection('products')"><img src="/LocalBakehouse/icons/brown_cake3.png" height="30px" width="30px">&nbsp;&nbsp;<div class="button-text">Explore sweet delights</div></button>
            </div>

            <div class="frame2">
                <div class="our-story">
                    <h2>Our Story</h2>
                    <?php
                        if ($bakeryInfo!=null) {
                            echo "<p>".htmlspecialchars($bakeryInfo['b_description'])."</p>";
                        } else {
                            echo "<p>Our story has not been added yet!</p>";
                        }
                    ?>
                </div>
                <div class="bakery-images" onclick="window.location.href='/LocalBakehouse/website/gallery.php'">
                    <?php if($bakeryImage!=null){while ($row = $bakeryImage->fetch_assoc()) { ?>
                        <img src="<?php echo htmlspecialchars($row['i_image']); ?>" alt="Bakery Image">
                    <?php }}else{ echo "<div class='our-story'><p>Bakery images has not been added yet!</p></div>";}?>
                </div>
            </div>
            
            <div class="frame3" id="products">
                <h2>Our Products</h2>
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
                            <div class="product-item" onclick="openModal('<?php echo htmlspecialchars($product['p_image']); ?>', '<?php echo htmlspecialchars($product['p_description']); ?>', '<?php echo htmlspecialchars($product['p_name']); ?>', '<?php echo htmlspecialchars($product['p_price']); ?>')">
                                <img src="<?php echo htmlspecialchars($product['p_image']); ?>" alt="Product Image" >
                                <p id="p-name"><?php echo htmlspecialchars($product['p_name']); ?></p>
                                <p>Rs. <?php echo htmlspecialchars($product['p_price']); ?></p>
                            </div>
                    <?php } ?>
                    <?php 
                    } else {
                        echo "<p id='no-info'>Products has not been added yet!</p>";
                    } ?>
                </div>

                <button class="view-all-products-btn" onclick="window.location.href='/LocalBakehouse/website/product.php?category=<?php echo $category; ?>'">View all Products&nbsp;&nbsp;<img src="/LocalBakehouse/icons/arrow.png" height="20px" width="20px"/></button>
            </div>

            <div class="frame4">
                <div class="contact-info">
                    <h2>Get in touch</h2>
                    <div class="location"><img src="/LocalBakehouse/icons/location.png"/>&nbsp;&nbsp;&nbsp;
                        <?php
                            if($bakeryInfo!=null){
                                echo "<p>".htmlspecialchars($bakeryInfo['b_address'])."</p>";
                            }else{
                                echo "<p>Bakery info has not been added yet!</p>";
                            }
                        ?>
                    </div>
                    <div class="contact"><img src="/LocalBakehouse/icons/contact.png"/>&nbsp;&nbsp;&nbsp;
                        <?php
                            if($bakeryInfo!=null){
                                echo "<p>".htmlspecialchars($bakeryInfo['b_contact_number'])."</p>";
                            }else{
                                echo "<p>Bakery info has not been added yet!</p>";
                            }
                        ?>
                    </div>
                    <div class="email"><img src="/LocalBakehouse/icons/email.png"/>&nbsp;&nbsp;&nbsp;
                        <?php
                            if($bakeryInfo!=null){
                                echo "<p>".htmlspecialchars($bakeryInfo['b_email'])."</p>";
                            }else{
                                echo "<p>Bakery info has not been added yet!</p>";
                            }
                        ?>
                    </div>
                    <button class="contact-btn">Contact us</button>
                </div>
                <div>
                    <iframe
                        src="https://www.google.com/maps?q=<?php if ($bakeryInfo!=null){echo urlencode($bakeryInfo['b_address']);}else{ echo "Kathmandu, Nepal";} ?>&output=embed" width="400" height="320" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>
            </div>
        </div>

        <footer>
            <div class="footer-content">
                <div class="footer-logo">
                    <?php 
                        if ($bakeryInfo!=null) {
                            echo "<img src='".htmlspecialchars($bakeryInfo['b_logo'])."' alt='Bakery Logo'>";
                            echo "<p>".htmlspecialchars($bakeryInfo['b_name'])."&nbsp;&nbsp;|</p>";
                        } else {
                            echo "<img src='/LocalBakehouse/icons/logo2.png' alt='Bakery Logo' style='height:40px; width:50px;'>";
                            echo "<p>Bakery&nbsp;&nbsp;|</p>";
                        }
                    ?>
                </div>
                <div class="footer-info1">
                    <?php 
                        if ($bakeryInfo!=null) {
                            echo "<p>".htmlspecialchars($bakeryInfo['b_address'])."&nbsp;&nbsp;|</p>";
                        } else {
                            echo "<p>Adress not added yet&nbsp;&nbsp;|</p>";
                        }
                    ?>
                </div>
                <div class="footer-info2">
                    <?php 
                        if ($bakeryInfo!=null) {
                            echo "<p>".htmlspecialchars($bakeryInfo['b_contact_number'])."&nbsp;&nbsp;|</p>";
                        } else {
                            echo "<p>Contact number not added yet&nbsp;&nbsp;|</p>";
                        }
                    ?>
                </div>
                <div class="footer-rights">
                    <?php 
                        if ($bakeryInfo!=null) {
                            echo "<p>&copy; ".date('Y')." ".htmlspecialchars($bakeryInfo['b_name']).". All rights reserved.</p>";
                        } else {
                            echo "<p>&copy;</p>";
                        }
                    ?>
                </div>
            </div>
        </footer>
        
        <div id="myModal" class="modal">
            <div class="modal-content">
                <button class="close" onclick="closeModal()">&times;</button>
                <div class="modal-body">
                    <div class="modal-image">
                        <img id="modalImage">
                    </div>
                    <div id="modalTitle" class="modal-title"></div>
                    <div id="modalDescription" class="modal-description"></div>
                    <div id="modalPrice" class="modal-price"></div>
                </div>
            </div>
        </div>

    </body>
    <script>
            function scrollToSection(id) {
                document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
            }

            function filterCategory(category, event) {
                const buttons = document.querySelectorAll('.cat-buttons button');
                buttons.forEach(button => button.classList.remove('active')); 
                event.target.classList.add('active');
                
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '/LocalBakehouse/website/home.php?category=' + category, true);
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
            
            function openModal(imageSrc, description, title, price) {
                var modal = document.getElementById("myModal");
                var modalImg = document.getElementById("modalImage");
                var modalDesc = document.getElementById("modalDescription");
                var modalTitle = document.getElementById("modalTitle");
                var modalPrice = document.getElementById("modalPrice");

                modal.style.display = "block";
                modalImg.src = imageSrc;
                modalDesc.innerHTML = description;
                modalTitle.innerHTML = title;
                modalPrice.innerHTML = "Rs. " + price;                
            }

            function closeModal() {
                var modal = document.getElementById("myModal");
                modal.style.display = "none";
            }
        </script>
</html>

<?php
    $conn->close();
?>
