<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/admin.css">
    <title>Admin</title>
</head>
<body>

    <nav class="navbar">
        <div class="nav-logout">
            <a href="../uploads/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
        </div>
    </nav>
    <div class="sidebar">
        <header> <img src="../assets/qcu-logo.png" alt="qcu-logo">QCU | DIGIMART</header>
        <ul>
            <li id="dashboard"><a href="dashboard.php"><i class="fa-solid fa-chart-simple"></i>Dashboard</a></li>
            <li id="listings" class="dropdown" onclick="toggleDropdown()">
                <div class="dropdown-show">
                    <a href="#"><i class="fa-solid fa-rectangle-list"></i>Pendings</a>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <ul class="dropdown-content">
                    <li><a href="pending-sellers.php" id="pending-sellers"><i class="fa-solid fa-store"></i>Sellers</a></li>
                    <li><a href="pending-products.php" id="pending-products"><i class="fa-solid fa-basket-shopping"></i>Products</a></li>
                </ul>
            </li>
            <li id="accounts-li"><a href="accounts.php"><i class="fa-solid fa-users"></i>Accounts</a></li>
            <li id="reports"><a href="reports.php"><i class="fa-solid fa-flag"></i>Reports</a></li>
        </ul>

    </div>
    <script src="script.js">

    </script>

</body>
</html>
