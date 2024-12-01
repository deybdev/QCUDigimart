<?php
session_start();
include '../config/config.php'; // Adjust path to your database connection file

// Initialize counts
$activeListings = 0;
$pendingListings = 0;
$outOfStock = 0;

// Fetch counts from the database
if (isset($_SESSION['seller_id'])) {
    $seller_id = $_SESSION['seller_id'];

    // Query for active listings
    $queryActive = "SELECT COUNT(*) AS count FROM product WHERE s_id = ? AND quantity > 4";
    $stmt = $conn->prepare($queryActive);
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $activeListings = $result->fetch_assoc()['count'] ?? 0;

    // Query for pending listings
    $queryPending = "SELECT COUNT(*) AS count FROM pending_products WHERE s_id = ?";
    $stmt = $conn->prepare($queryPending);
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pendingListings = $result->fetch_assoc()['count'] ?? 0;

    // Query for out-of-stock products
    $queryOutOfStock = "SELECT COUNT(*) AS count FROM product WHERE s_id = ? AND quantity = 0";
    $stmt = $conn->prepare($queryOutOfStock);
    $stmt->bind_param('i', $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $outOfStock = $result->fetch_assoc()['count'] ?? 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
    <?php include '../seller/sidebar.php'; ?>
    
        <div class="wrapper">
            <div class="link-button">
                <a href="../main/home.php">Home</a><span>|</span>
                <a href="../main/about.php">About Us</a><span>|</span>
                <a href="#">Contact</a>
            </div>

            <h2 style="margin: 30px 0;">Dashboard</h2>

            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <span>Active Listings</span>
                        <div class="icon green-circle"></div>
                    </div>
                    <div class="card-value"><?php echo $activeListings; ?></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <span>Pending Listings</span>
                        <div class="icon purple-triangle"></div>
                    </div>
                    <div class="card-value"><?php echo $pendingListings; ?></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <span>Out Of Stock</span>
                        <div class="icon yellow-square"></div>
                    </div>
                    <div class="card-value"><?php echo $outOfStock; ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
