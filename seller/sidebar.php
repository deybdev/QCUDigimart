<?php
if (!isset($_SESSION['seller_id']) || !isset($_SESSION['store_name'])) {
    header("Location: login.php"); // Redirect to login page
    exit;
}

$enterprise_name = isset($_SESSION['store_name']) ? htmlspecialchars($_SESSION['store_name']) : "Guest";

$store_profile = !empty($_SESSION['seller_profile']) ? htmlspecialchars($_SESSION['seller_profile']) : '../assets/qcu-logo.png';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/transcss.css">
    <title>Sidebar</title>
</head>
<body>
    <div class="sidebar">

        <?php include "../main/modal.php";?>

        <header> <img src="<?php echo $store_profile; ?>" alt="qcu-logo"><?php echo $enterprise_name; ?></header>
        <ul>
            <li id="dashboard"><a href="dashboard.php"><i class="fa-solid fa-chart-simple"></i>Dashboard</a></li>
            <li id="listings"><a href="listings.php"><i class="fa-solid fa-clipboard-list"></i>Listings</a></li>
            <li id="add_product"><a href="add_product.php"><i class="fa-solid fa-plus"></i>Add Product</a></li>
            <li id="edit_shop"><a href="edit_shop.php"><i class="fa-solid fa-pen-to-square"></i>Edit Shop</a></li>
            <li><a href="../uploads/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a></li>
        </ul>
    </div>
    <script>
            document.addEventListener("DOMContentLoaded", function() {
                const currentLocation = window.location.href;

                // Apply active class based on the page
                if (currentLocation.includes("dashboard.php")) {
                    document.getElementById("dashboard").classList.add("active");
                } else if (currentLocation.includes("listings.php")) {
                    document.getElementById("listings").classList.add("active");
                } else if (currentLocation.includes("add_product.php")) {
                    document.getElementById("add_product").classList.add("active");
                } else if (currentLocation.includes("edit_shop.php")) {
                    document.getElementById("edit_shop").classList.add("active");
                }
            });
    </script>

</body>
</html>