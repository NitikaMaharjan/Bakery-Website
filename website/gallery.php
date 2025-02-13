<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "localbakehouse";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlBakeryInfo = "SELECT * FROM bakeryinfo WHERE b_id = 1";
    $bakeryInfo = $conn->query($sqlBakeryInfo)->fetch_assoc();

    $sqlBakeryImages = "SELECT * FROM bakeryimage";
    $bakeryImages = $conn->query($sqlBakeryImages);

    $sqlProductImages = "SELECT * FROM productinfo ORDER BY created_at DESC";
    $productImages = $conn->query($sqlProductImages);
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
        <link rel="stylesheet" href="/LocalBakehouse/css/gallery.css" type="text/css">
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
            <h1>Gallery</h1>

            <div class="buttons">
                <button id="bakeryButton" class="active-button" onclick="showBakeryImages()">Bakery Images</button>
                <button id="productButton" class="inactive-button" onclick="showProductImages()">Product Images</button>
            </div>

            <div class="gallery">

                <div id="bakeryImages" style="display: block;">
                    <?php while ($row1 = $bakeryImages->fetch_assoc()) { ?>
                        <img src="<?php echo htmlspecialchars($row1['i_image']); ?>" alt="Bakery Image" onclick="openModal('<?php echo htmlspecialchars($row1['i_image']); ?>', '<?php echo htmlspecialchars($row1['i_description']); ?>', null, null)">
                    <?php } ?>
                </div>

                <div id="productImages" style="display: none;">
                    <?php while ($row2 = $productImages->fetch_assoc()) { ?>
                        <img src="<?php echo htmlspecialchars($row2['p_image']); ?>" alt="Product Image" onclick="openModal('<?php echo htmlspecialchars($row2['p_image']); ?>', '<?php echo htmlspecialchars($row2['p_description']); ?>', '<?php echo htmlspecialchars($row2['p_name']); ?>', '<?php echo htmlspecialchars($row2['p_price']); ?>')">
                    <?php } ?>
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
        function openModal(imageSrc, description, title, price) {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("modalImage");
            var modalDesc = document.getElementById("modalDescription");
            var modalTitle = document.getElementById("modalTitle");
            var modalPrice = document.getElementById("modalPrice");

            modal.style.display = "block";
            modalImg.src = imageSrc;

            if (title && price) {
                modalTitle.innerHTML = title;
                modalPrice.innerHTML = "Rs. " + price;
            } else {
                modalTitle.innerHTML = '';
                modalPrice.innerHTML = '';  
            }
            modalDesc.innerHTML = description;
        }

        function closeModal() {
            var modal = document.getElementById("myModal");
            modal.style.display = "none";
        }

        function showBakeryImages() {
            document.getElementById("bakeryImages").style.display = "block";
            document.getElementById("productImages").style.display = "none";

            document.getElementById("bakeryButton").classList.add("active-button");
            document.getElementById("bakeryButton").classList.remove("inactive-button");
            document.getElementById("productButton").classList.add("inactive-button");
            document.getElementById("productButton").classList.remove("active-button");
        }

        function showProductImages() {
            document.getElementById("productImages").style.display = "block";
            document.getElementById("bakeryImages").style.display = "none";

            document.getElementById("productButton").classList.add("active-button");
            document.getElementById("productButton").classList.remove("inactive-button");
            document.getElementById("bakeryButton").classList.add("inactive-button");
            document.getElementById("bakeryButton").classList.remove("active-button");
        }

        window.onload = function () {
            showBakeryImages();
        };

    </script>
</html>

<?php
    $conn->close();
?>
