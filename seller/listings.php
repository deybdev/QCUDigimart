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
        SELECT id, name, price, is_available, images, date_created, 'Active' AS status 
        FROM product
        WHERE s_id = ? 
        AND id NOT IN (SELECT id FROM pending_products WHERE s_id = ?)
    ";
    $params[] = $seller_id;
    $params[] = $seller_id;
}

// Pending products
if ($filter_status === 'all' || $filter_status === 'pending') {
    if ($sql !== "") $sql .= " UNION ALL ";
    $sql .= "
        SELECT id, name, price, is_available, images, date_created, 'Pending' AS status 
        FROM pending_products 
        WHERE s_id = ? 
    ";
    $params[] = $seller_id;
}

// Rejected products
if ($filter_status === 'all' || $filter_status === 'rejected') {
    if ($sql !== "") $sql .= " UNION ALL ";
    $sql .= "
        SELECT id, name, price, is_available, images, date_rejected AS date_created, 'Rejected' AS status 
        FROM rejected_products 
        WHERE s_id = ? 
    ";
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
                <a href="../main/main-home.php">Home</a><span>|</span>
                <a href="../main/about.php">About Us</a><span>|</span>
                <a href="../main/contact.php">Contact</a>
            </div>
            <h2>Category</h2>
            <div class="listing-link">
                <a href="listings.php?status=all" class="<?php echo ($filter_status === 'all') ? 'active' : ''; ?>">All</a><span> | </span>
                <a href="listings.php?status=active" class="<?php echo ($filter_status === 'active') ? 'active' : ''; ?>">Active</a><span> | </span>
                <a href="listings.php?status=pending" class="<?php echo ($filter_status === 'pending') ? 'active' : ''; ?>">Pending</a><span> | </span>
                <a href="listings.php?status=rejected" class="<?php echo ($filter_status === 'rejected') ? 'active' : ''; ?>">Rejected</a><span> | </span>
            </div>
            <div class="seller-listing-table">
                <table id="listing-table">
                    <thead>
                        <tr>
                            <th>Details</th>
                            <th>Listing Information</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        $images = json_decode($row['images'], true);
                        $firstImage = $images && isset($images[0]) ? $images[0] : 'default.jpg';
                        
                        $status = $row['status'];
                        $statusClass = match ($status) {
                            'Active' => 'status-active',
                            'Pending' => 'status-pending',
                            'Rejected' => 'status-rejected',
                            default => '',
                        };

                        $availability = $row['is_available'] ? 'Available' : 'Unavailable';
                        $availabilityClass = $row['is_available'] ? 'status-available' : 'status-unavailable';
                        $toggleText = $row['is_available'] ? 'Set Unavailable' : 'Set Available'; // Button text
                    ?>
                    <tr>
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
                                <p> <span class="<?php echo $availabilityClass; ?>">
                                        <?php echo $availability; ?>
                                    </span>
                                </p>
                                <p><strong>Status:</strong> 
                                    <span style="background-color: transparent;" class="<?php echo $statusClass; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </p>
                            </div>
                        </td>
                        <td class="actions-cell">
                            <div class="action-buttons">
                                <!-- Show buttons only if the product is 'Active' -->
                                <?php if ($status === 'Active') { ?>
                                    <button class="toggle-availability-btn" data-id="<?php echo $row['id']; ?>" data-available="<?php echo $row['is_available']; ?>" style="background-color:blue;">
                                        <?php echo $toggleText; ?>
                                    </button>
                                    <button class="remove-product-btn" 
                                            data-id="<?php echo $row['id']; ?>" 
                                            data-status="<?php echo $row['status']; ?>">Remove Product
                                    </button>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Toggle availability functionality
            document.querySelectorAll('.toggle-availability-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-id');

                    if (confirm('Are you sure you want to toggle availability?')) {
                        fetch('toggle_availability.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: productId }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Failed to toggle availability: ' + data.message);
                            }
                        });
                    }
                });
            });

            // Remove product functionality
            document.querySelectorAll('.remove-product-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-id');
                    const productStatus = button.getAttribute('data-status');

                    if (confirm('Are you sure you want to remove this product?')) {
                        fetch('remove_product.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: productId, status: productStatus }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert('Failed to remove product: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while removing the product.');
                        });
                    }
                });
            });
        });

    </script>
</body>
</html>
