<?php
session_start();
include '../config/config.php';

if (!isset($_GET['seller_id'])) {
    echo "Seller not found.";
    exit;
}

$seller_id = intval($_GET['seller_id']);

// Fetch seller details
$seller_query = $conn->prepare("SELECT * FROM seller WHERE id = ?");
$seller_query->bind_param("i", $seller_id);
$seller_query->execute();
$seller_result = $seller_query->get_result();

if ($seller_result->num_rows === 0) {
    echo "Seller not found.";
    exit;
}

$seller = $seller_result->fetch_assoc();

// Fetch seller's products
$product_query = $conn->prepare("
    SELECT * FROM product 
    WHERE s_id = ?");
$product_query->bind_param("i", $seller_id);
$product_query->execute();
$products = $product_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seller['store_name']); ?></title>
</head>
<body>
    <?php include "../main/header.php"; ?>

    <div class="store-banner" style="background: url('<?php echo htmlspecialchars($seller['store_banner'] ?? 'default-banner.jpg'); ?>') center/cover no-repeat;">
        <div class="store-info">
            <div class="store-logo">
                <img src="<?php echo htmlspecialchars($seller['store_profile']);?>" alt="Store Logo">
            </div>
            <div class="store-details">
                <h4 class="store-name"><?php echo htmlspecialchars($seller['store_name']); ?></h4>
            </div>
        </div>
        <div class="store-actions">
            <button class="action-button message" onclick="messageSeller(<?php echo $seller_id; ?>)">Message</button>
        </div>

    </div>

    <div class="store-desc-container">
        <h2>STORE DESCRIPTION</h2>
        <div class="store-description">
            <p><?php echo nl2br(htmlspecialchars($seller['description'])); ?></p>
        </div>
    </div>

    <div class="seller-product-page">
        <h2>OUR PRODUCTS</h2>
        <div class="seller-product">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="seller-product-card">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars(json_decode($product['images'], true)[0] ?? 'default.jpg'); ?>" alt="Image">
                    </div>
                    <div class="prod-info">
                        <h4 class="prod-name"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <p class="prod-description"> <?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="prod-price">₱ <?php echo htmlspecialchars($product['price']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <?php include "../main/footer.php"; ?>

    <script>
        function messageSeller(sellerId) {
            // Check if the user is logged in
            <?php if (!isset($_SESSION['customer_id'])) { ?>
                showInfoModal('Please login first to message the seller.');
                return;
            <?php } ?>

            // Redirect to the chat page with the seller ID
            window.location.href = `../message/chat.php?receiver_id=${sellerId}`;
        }
    </script>
</body>
</html>
