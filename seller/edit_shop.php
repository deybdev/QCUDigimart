<?php
session_start();
include "../config/config.php";

// Fetch seller_id from session
$seller_id = $_SESSION['seller_id'];

// Fetch seller details from the database
$query = "SELECT * FROM seller WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

if (!$seller) {
    die("Seller not found!");
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'] ?? '';
    $banner = $_FILES['banner'] ?? null;
    $logo = $_FILES['logo'] ?? null;

    $uploadDir = '../assets/';
    $bannerPath = $seller['store_banner'];
    $logoPath = $seller['store_profile'];

    // Handle banner upload
    if ($banner && $banner['error'] === UPLOAD_ERR_OK) {
        $originalBannerName = pathinfo($banner['name'], PATHINFO_FILENAME);
        $bannerExt = pathinfo($banner['name'], PATHINFO_EXTENSION);
        $bannerPath = $uploadDir . $originalBannerName . '.' . $bannerExt;

        if (!move_uploaded_file($banner['tmp_name'], $bannerPath)) {
            die("Failed to upload banner.");
        }
    }

    // Handle logo upload
    if ($logo && $logo['error'] === UPLOAD_ERR_OK) {
        $originalLogoName = pathinfo($logo['name'], PATHINFO_FILENAME);
        $logoExt = pathinfo($logo['name'], PATHINFO_EXTENSION);
        $logoPath = $uploadDir . $originalLogoName . '.' . $logoExt;

        if (!move_uploaded_file($logo['tmp_name'], $logoPath)) {
            die("Failed to upload logo.");
        }
    }

    // Update seller details in the database
    $query = "UPDATE seller SET store_banner = ?, store_profile = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $bannerPath, $logoPath, $description, $seller_id);

    if ($stmt->execute()) {
        header("Location: edit_shop.php");
        exit;
    } else {
        echo "Error updating shop: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include '../seller/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div class="wrapper">
            <div class="edit-container">
                <div class="link-button">
                    <a href="../main/home.php">Home</a><span>/</span>
                    <a href="../main/about.php">About Us</a><span>/</span>
                    <a href="#">Contact</a>
                </div>

                <!-- Shop Preview -->
                <h2>Preview</h2>
                <div class="preview" style="background-image: url('<?php echo htmlspecialchars($seller['store_banner']); ?>'); background-size: cover; background-position: center;">
                    <div class="shop-info">
                        <div class="shop-logo">
                            <img src="<?php echo htmlspecialchars($seller['store_profile']); ?>" alt="Shop Logo">
                        </div>
                        <div class="shop-name"><?php echo htmlspecialchars($seller['store_name']); ?></div>
                    </div>
                </div>

                <!-- Shop Edit Form -->
                <h2>Shop Details</h2>
                <form action="edit_shop.php" method="POST" enctype="multipart/form-data">
                    <div class="add-shop-image">
                        <!-- Banner Upload -->
                        <div class="add-image" onclick="document.getElementById('fileInputBanner').click();">
                            <i class="fa-solid fa-image"></i>
                            <p>Change Banner</p>
                            <input type="file" id="fileInputBanner" name="banner" style="display: none;">
                        </div>

                        <!-- Logo Upload -->
                        <div class="add-image" onclick="document.getElementById('fileInputLogo').click();">
                            <i class="fa-solid fa-image"></i>
                            <p>Change Logo</p>
                            <input type="file" id="fileInputLogo" name="logo" style="display: none;">
                        </div>

                        <!-- Description -->
                        <div class="shop-description">
                            <textarea name="description" placeholder="Add a shop description"><?php echo htmlspecialchars($seller['description'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="edit-shop-button">
                        <button type="submit" class="btn">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Handle file input for banner preview
        document.getElementById('fileInputBanner').onchange = function (event) {
            const [file] = event.target.files;
            if (file) {
                document.querySelector('.preview').style.backgroundImage = `url(${URL.createObjectURL(file)})`;
            }
        };

        // Handle file input for logo preview
        document.getElementById('fileInputLogo').onchange = function (event) {
            const [file] = event.target.files;
            if (file) {
                document.querySelector('.shop-logo img').src = URL.createObjectURL(file);
            }
        };
    </script>
</body>
</html>
