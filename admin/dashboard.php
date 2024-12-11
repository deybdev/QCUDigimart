<?php
include '../config/config.php';

// Query to get the count of sellers
$seller_count_sql = "SELECT COUNT(*) AS seller_count FROM seller";
$seller_count_result = $conn->query($seller_count_sql);
$seller_count = $seller_count_result->fetch_assoc()['seller_count'];

// Query to get the count of customers
$customer_count_sql = "SELECT COUNT(*) AS customer_count FROM customer";
$customer_count_result = $conn->query($customer_count_sql);
$customer_count = $customer_count_result->fetch_assoc()['customer_count'];

// Query to get the count of pending sellers
$pending_sellers_sql = "SELECT COUNT(*) AS pending_sellers FROM pending_sellers";
$pending_sellers_result = $conn->query($pending_sellers_sql);
$pending_sellers = $pending_sellers_result->fetch_assoc()['pending_sellers'];

// Query to get the count of pending products
$pending_products_sql = "SELECT COUNT(*) AS pending_products FROM pending_products";
$pending_products_result = $conn->query($pending_products_sql);
$pending_products = $pending_products_result->fetch_assoc()['pending_products'];

// Calculate total pendings
$total_pendings = $pending_sellers + $pending_products;

// Query to get the count of reports
$reports_count_sql = "SELECT COUNT(*) AS reports_count FROM reports";
$reports_count_result = $conn->query($reports_count_sql);
$reports_count = $reports_count_result->fetch_assoc()['reports_count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
    <div class="wrapper">
        <h2 class="title" style="text-align: center; margin-top:20px">Dashboard</h2>
        <div class="dashboard-containers">
            <div class="container">
                <p>Sellers</p>
                <div class="cont">
                    <div class="icon yellow"></div>
                    <h2><?php echo $seller_count; ?></h2>
                </div>
            </div>
            <div class="container">
                <p>Customers</p>
                <div class="cont">
                    <div class="icon green"></div>
                    <h2><?php echo $customer_count; ?></h2>
                </div>
            </div>
            <div class="container">
                <p>Pendings</p>
                <div class="cont">
                    <div class="icon blue"></div>
                    <h2><?php echo $total_pendings; ?></h2>
                </div>
            </div>
            <div class="container">
                <p>Reports</p>
                <div class="cont">
                    <div class="icon red"></div>
                    <h2><?php echo $reports_count; ?></h2>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
