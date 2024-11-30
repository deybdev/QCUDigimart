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
            <!-- MONITORING START-->
            <div class="monitoring-container">
                <div class="header">
                    <h2>Monitoring</h2>
                    <a href="pending-products.php">See all</a>
                </div>
                <div class="food-container">
                    <div class="food-cards">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="card-info">
                                        <p class="seller-name">Store Name 1</p>
                                        <p class="food-name">Food Name 1</p>
                                    </div>
                                    <div class="header-icon">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                </div>
                                <img src="../assets/t1.jpg" alt="Food Image 2">
                            </div>
                        </div>
                    </div>
                    <div class="food-cards">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="card-info">
                                        <p class="seller-name">Store Name 2</p>
                                        <p class="food-name">Food Name 2</p>
                                    </div>
                                    <div class="header-icon">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                </div>
                                <img src="../assets/ent1.jpg" alt="Food Image 2">
                            </div>
                        </div>
                    </div>
                    <div class="food-cards">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-header">
                                    <div class="card-info">
                                        <p class="seller-name">Store Name 3</p>
                                        <p class="food-name">Food Name 3</p>
                                    </div>
                                    <div class="header-icon">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </div>
                                </div>
                                <img src="../assets/ent2.jpg" alt="Food Image 2">
                            </div>
                        </div>
                    </div>
                </div>
    </div>
            <!-- MONITORING END-->
    </div>
</body>
</html>