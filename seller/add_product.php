<?php
include '../config/config.php';
session_start();

$category_id = null;
$category_name = ''; // New variable for storing category name

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_id = $_SESSION['seller_id'];
    
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Check if category is "Other"
    if ($_POST['category'] === 'other' && !empty($_POST['other_category'])) {
        $new_category = $_POST['other_category'];

        // Check if category already exists
        $check_category = $conn->prepare("SELECT id FROM category WHERE name = ?");
        $check_category->bind_param("s", $new_category);
        $check_category->execute();
        $result = $check_category->get_result();

        if ($result->num_rows > 0) {
            // If category exists, use the existing ID
            $category_row = $result->fetch_assoc();
            $category_id = $category_row['id'];
            $category_name = $new_category; // Store the "Other" category name
        } else {
            // Insert the new category
            $insert_category = $conn->prepare("INSERT INTO category (name) VALUES (?)");
            $insert_category->bind_param("s", $new_category);
            $insert_category->execute();
            $category_id = $insert_category->insert_id;  // Get the ID of the new category
            $category_name = $new_category; // Store the newly added category name
            $insert_category->close();
        }
    } else {
        // If category is not "Other", use the selected category
        $category_id = $_POST['category'];

        // Get the category name for the selected category_id
        $get_category_name = $conn->prepare("SELECT name FROM category WHERE id = ?");
        $get_category_name->bind_param("i", $category_id);
        $get_category_name->execute();
        $result = $get_category_name->get_result();
        if ($result->num_rows > 0) {
            $category_row = $result->fetch_assoc();
            $category_name = $category_row['name']; // Store the selected category name
        }
    }

    // Ensure category_id is not null before proceeding
    if (is_null($category_id) || empty($category_name)) {
        echo "<p>Category selection is required!</p>";
        exit;
    }

    // Handle multiple image uploads
    $image_paths = []; // Array to store paths of uploaded images
    $target_dir = "../assets/products/";

    if (!empty($_FILES['images']['name'][0])) { // Check if there are files uploaded
        foreach ($_FILES['images']['tmp_name'] as $index => $tmp_name) {
            $file_name = basename($_FILES['images']['name'][$index]);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $image_paths[] = $target_file; // Add path to array if upload was successful
            }
        }
    }

    // Encode the array of image paths as JSON to store in the database
    $image_paths_json = json_encode($image_paths);

    // Insert product into pending_products table
    $sql = "INSERT INTO pending_products (name, description, price, quantity, category_id, category, images, s_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssdisssi", $name, $description, $price, $quantity, $category_id, $category_name, $image_paths_json, $s_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Product added successfully!';
    } else {
        echo "<p>Error adding product: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch categories for dropdown (only after all database interactions are done)
$sql_categories = "SELECT id, name FROM category";
$stmt_categories = $conn->prepare($sql_categories);
$stmt_categories->execute();
$categories = $stmt_categories->get_result();

// Close the connection after all database interactions are completed
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    
    <div class="container">

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

        <?php include '../seller/sidebar.php'; ?>
        <div class="wrapper">
            <div class="link-button">
                <a href="../main/main-home.php">Home </a><span>|</span>
                <a href="../main/about.php">About Us </a><span>|</span>
                <a href="#">Contact</a>
            </div>
            <div class="add-pro-header">
                <h2>Add Product</h2>
            </div>
            <div class="add-product-container">
                <form action="add_product.php" method="post" autocomplete="off" enctype="multipart/form-data">
                    <div class="image-upload-container">
                        <div class="file-inputs">
                            <input type="file" name="images[]" accept="image/*" required>
                        </div>
                        <button type="button" class="btn" id="add-image-button">Add Another Image</button>
                    </div>
                    
                    <div class="form-fields">
                        <input type="text" name="name" placeholder="Name" required>
                        <input type="number" name="price" placeholder="Price" required>
                        <input type="number" name="quantity" placeholder="Quantity" required>
                        
                        <!-- Category Dropdown -->
                        <select name="category" id="category-dropdown" required>
                            <option value="" style="color: #ddd;">Select Category</option>
                            <?php while ($category = $categories->fetch_assoc()) : ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endwhile; ?>
                            <option value="other">Other (Enter New Category)</option>
                        </select>

                        <!-- Text input for "Other" category, initially hidden -->
                        <div id="other-category-container" style="display: none;">
                            <input type="text" name="other_category" id="other-category" placeholder="Enter new category name" />
                        </div>

                        <textarea name="description" placeholder="Description" required></textarea>
                        <button type="submit" class="pub-btn btn">Publish Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>

        // JavaScript to dynamically add new file input fields
        document.getElementById('add-image-button').addEventListener('click', function() {
            const fileInputsContainer = document.querySelector('.file-inputs');
            const newFileInput = document.createElement('input');
            newFileInput.type = 'file';
            newFileInput.name = 'images[]';
            newFileInput.accept = 'image/*';
            fileInputsContainer.appendChild(newFileInput);
        });

        // Show the input field for "Other" category when selected
        document.getElementById('category-dropdown').addEventListener('change', function() {
            var otherCategoryInput = document.getElementById('other-category-container');
            if (this.value === 'other') {
                otherCategoryInput.style.display = 'flex'; // Show input
            } else {
                otherCategoryInput.style.display = 'none'; // Hide input
            }
        });
    </script>
</body>
</html>
