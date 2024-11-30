<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['seller_id'])) {
    die("Seller ID is not set in session.");
}

$seller_id = $_SESSION['seller_id'];
$filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';
$sql = "";
$params = [];

// Active products (exclude products in pending_products to avoid duplication)
if ($filter_status === 'all' || $filter_status === 'active') {
    $sql .= "
        SELECT id, name, price, quantity, images, date_created, 'Active' AS status 
        FROM product
        WHERE s_id = ? 
        AND id NOT IN (SELECT id FROM pending_products WHERE s_id = ?)
        AND quantity >= 3
    ";
    $params[] = $seller_id;
    $params[] = $seller_id;
}

// Pending products
if ($filter_status === 'all' || $filter_status === 'pending') {
    if ($sql !== "") $sql .= " UNION ALL ";
    $sql .= "
        SELECT id, name, price, quantity, images, date_created, 'Pending' AS status 
        FROM pending_products 
        WHERE s_id = ? 
    ";
    $params[] = $seller_id;
}

// Rejected products
if ($filter_status === 'all' || $filter_status === 'rejected') {
    if ($sql !== "") $sql .= " UNION ALL ";
    $sql .= "
        SELECT id, name, price, quantity, images, date_rejected AS date_created, 'Rejected' AS status 
        FROM rejected_products 
        WHERE s_id = ? 
    ";
    $params[] = $seller_id;
}

// Out-of-stock products (quantity < 3, excluding products already pending)
if ($filter_status === 'all' || $filter_status === 'outofstock') {
    if ($sql !== "") $sql .= " UNION ALL ";
    $sql .= "
        SELECT id, name, price, quantity, images, date_created, 'Out of Stock' AS status 
        FROM product
        WHERE s_id = ? 
        AND quantity < 3
        AND id NOT IN (SELECT id FROM pending_products WHERE s_id = ?)
    ";
    $params[] = $seller_id;
    $params[] = $seller_id;
}

// Sort by date
$sql .= " ORDER BY date_created DESC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing SQL statement: " . $conn->error);
}

// Bind parameters dynamically based on the array
$types = str_repeat("i", count($params));
$stmt->bind_param($types, ...$params);

$stmt->execute();
$result = $stmt->get_result();
?>

<!-- HTML Output (Unchanged) -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings</title>
</head>
<body>
    <div class="container">
        <?php include '../seller/sidebar.php'; ?>
        <div class="wrapper">
            <div class="link-button">
                <a href="../main/home.php">Home </a><span>/</span>
                <a href="../main/about.php">About Us </a><span>/</span>
                <a href="#">Contact</a>
            </div>
            <h2>Category</h2>
            <div class="listing-link">
                <a href="listings.php?status=all" class="<?php echo ($filter_status === 'all') ? 'active' : ''; ?>">All</a><span> | </span>
                <a href="listings.php?status=active" class="<?php echo ($filter_status === 'active') ? 'active' : ''; ?>">Active</a><span> | </span>
                <a href="listings.php?status=pending" class="<?php echo ($filter_status === 'pending') ? 'active' : ''; ?>">Pending</a><span> | </span>
                <a href="listings.php?status=rejected" class="<?php echo ($filter_status === 'rejected') ? 'active' : ''; ?>">Rejected</a><span> | </span>
                <a href="listings.php?status=outofstock" class="<?php echo ($filter_status === 'outofstock') ? 'active' : ''; ?>">Out of Stock</a>
            </div>
            <div class="seller-listing-table">
                <table id="listing-table">
                    <thead>
                        <tr>
                            <th class="checkbox-cell"><input type="checkbox"></th>
                            <th>Details</th>
                            <th>Listing Information</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        // Decode the images JSON array and get the first image
                        $images = json_decode($row['images'], true);
                        $firstImage = $images && isset($images[0]) ? $images[0] : 'default.jpg'; // Use a default image if no images found
                        
                        // Logic for determining status and status class
                        $status = $row['status'];
                        $statusClass = match ($status) {
                            'Active' => 'status-active',
                            'Pending' => 'status-pending',
                            'Rejected' => 'status-rejected',
                            'Out of Stock' => 'status-outofstock',
                            default => '',
                        };
                    ?>
                        <tr>
                            <td class="checkbox-cell"><input type="checkbox"></td>
                            <td class="details-cell">
                                <div class="image-cell">
                                    <img src="../assets/<?php echo htmlspecialchars($firstImage); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                </div>
                                <div>
                                    <p class="date"><?php echo date("F j, Y", strtotime($row['date_created'])); ?></p>
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                </div>
                            </td>
                            <td class="listing-info-cell">
                                <div class="listing-info">
                                    <p><strong>Price:</strong> ₱<?php echo htmlspecialchars($row['price']); ?></p>
                                    <p><strong>Quantity:</strong> <?php echo htmlspecialchars($row['quantity']); ?></p>
                                    <p><strong>Status:</strong> 
                                        <span class="<?php echo $statusClass; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
