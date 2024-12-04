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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>


<?php include'sidebar.php'; ?>
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
                        <h2>125</h2>
                    </div>
                </div>
                <div class="container">
                    <p>Reports</p>
                    <div class="cont">
                        <div class="icon red"></div>
                        <h2>16</h2>
                    </div>
                </div>
            </div>
            <!-- MONITORING END-->
    </div>
</body>
</html>