<?php
session_start();
include "../config/config.php";

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo "Please log in to view your saved products.";
    exit;
}

$user_id = $_SESSION['customer_id'];

// Handle product removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_product_id'])) {
    $product_id = intval($_POST['remove_product_id']);

    $delete_query = "DELETE FROM saved_products WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("ii", $user_id, $product_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product removed successfully.";
    }
}

// Fetch saved products with product and seller details
$query = "
    SELECT 
        p.id AS product_id,
        p.name AS product_name,
        p.description AS product_description,
        p.price AS product_price,
        p.images AS product_images,
        s.store_name AS store_name
    FROM 
        saved_products sp
    JOIN 
        product p ON sp.product_id = p.id
    JOIN 
        seller s ON p.s_id = s.id
    WHERE 
        sp.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Products</title>
</head>
<body>
    <?php include "../main/header.php"; ?>
    <?php
    if (isset($_SESSION['info_message'])) {
        $infoMessage = htmlspecialchars(addslashes($_SESSION['info_message']));
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const infoMessage = "' . $infoMessage . '";
                    showInfoModal(infoMessage); 
                });
              </script>';
        unset($_SESSION['info_message']);
    }

    if (isset($_SESSION['success_message'])) {
        $infoMessage = htmlspecialchars(addslashes($_SESSION['success_message']));
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const infoMessage = "' . $infoMessage . '";
                    showSuccessModal(infoMessage); 
                });
              </script>';
        unset($_SESSION['success_message']);
    }
    ?>
    <div class="container">
        <div class="save-container">
            <h2>Your Saved Products <span>(<?php echo htmlspecialchars($result->num_rows); ?> Items)</span></h2>
            <?php if (isset($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <?php if ($result->num_rows > 0): ?>
            <div class="save-table-container">
                <table id="save-table">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Info</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): 
                            // Decode the images JSON and fetch the first image
                            $images = json_decode($row['product_images'], true);
                            $firstImage = $images && isset($images[0]) ? $images[0] : 'default.jpg';
                        ?>
                        <tr>
                            <td>
                                <div class="saved-img">
                                    <img src="<?php echo htmlspecialchars($firstImage); ?>" alt="Saved Product">
                                </div>
                            </td>
                            <td>
                                <div class="saved-info">
                                    <p><?php echo htmlspecialchars($row['store_name']); ?></p>
                                    <h3 class="prod-name"><?php echo htmlspecialchars($row['product_name']); ?></h3><br>
                                    <p class="prod-desc"><?php echo htmlspecialchars($row['product_description']); ?></p>
                                </div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['product_price']); ?>
                            </td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="remove_product_id" value="<?php echo htmlspecialchars($row['product_id']); ?>">
                                    <button type="submit" class="remove-cart">Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                            <td><p class="no-saved-products">You don't have any saved products yet.</p></td>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
    <?php include "../main/footer.php"; ?>
</body>
</html>
