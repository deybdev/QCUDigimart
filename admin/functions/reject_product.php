<?php
include 'C:/xampp/htdocs/qcudigimart/config/config.php';
session_start();

if (isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'reject') {
    $productId = $_POST['id'];

    // Fetch product details from pending_products with category name
    $selectSql = "
        SELECT p.*, c.name AS category
        FROM pending_products p
        JOIN category c ON p.category_id = c.id
        WHERE p.id = ?
    ";
    $selectStmt = $conn->prepare($selectSql);

    if ($selectStmt) {
        $selectStmt->bind_param("i", $productId);
        $selectStmt->execute();
        $result = $selectStmt->get_result();

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Debugging output to verify fetched data
            echo "<pre>";
            print_r($product);
            echo "</pre>";

            // Insert product into rejected_products with category name
            $insertSql = "
                INSERT INTO rejected_products (name, description, price, is_available, category, category_id, images, s_id, date_rejected) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ";
            $insertStmt = $conn->prepare($insertSql);

            if ($insertStmt) {
                $insertStmt->bind_param(
                    "ssdisisi",
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['is_available'],
                    $product['category'],     // Insert category name
                    $product['category_id'],
                    $product['images'],
                    $product['s_id']
                );

                if ($insertStmt->execute()) {
                    echo "Product successfully added to rejected products.<br>";

                    // Delete the product from pending_products
                    $deleteSql = "DELETE FROM pending_products WHERE id = ?";
                    $deleteStmt = $conn->prepare($deleteSql);

                    if ($deleteStmt) {
                        $deleteStmt->bind_param("i", $productId);
                        if ($deleteStmt->execute()) {
                            echo "Product successfully deleted from pending products.<br>";
                        } else {
                            echo "Error deleting product from pending products: " . $conn->error . "<br>";
                        }
                    } else {
                        echo "Error preparing delete statement: " . $conn->error . "<br>";
                    }

                    $deleteStmt->close();
                } else {
                    echo "Error inserting product into rejected products: " . $conn->error . "<br>";
                }

                $insertStmt->close();
            } else {
                echo "Error preparing insert statement: " . $conn->error . "<br>";
            }
        } else {
            echo "Product not found in pending products.<br>";
        }

        $selectStmt->close();
    } else {
        echo "Error preparing select statement: " . $conn->error . "<br>";
    }
} else {
    echo "No valid product ID or action received.<br>";
}

$conn->close();

?>
