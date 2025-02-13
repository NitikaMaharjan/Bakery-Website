<html>
    <head>
        <title>Bakery Management</title>
    </head>
</html>
<?php
    session_start();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
        session_unset();
        session_destroy();
        echo"<script>
                alert('You have been logged out!!');
                window.location.href = '/LocalBakehouse/html/login.html';
            </script>";
        exit();
    }  
?>
