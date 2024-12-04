<?php
include 'C:/xampp/htdocs/qcudigimart/config/config.php';
session_start();

if (isset($_POST['id'])) {
    $productId = $_POST['id'];

    // Prepare SQL to fetch the product details from pending_products
    $sql = "SELECT * FROM pending_products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();

            // Insert the product into the product table
            $insertSql = "INSERT INTO product (name, is_available, price, description, images, s_id, category_id, category, date_created)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())"; // Added 'NOW()' for date_created
            $insertStmt = $conn->prepare($insertSql);

            if ($insertStmt) {
                $insertStmt->bind_param(
                    "sdsssiis",  // Updated to match the parameter types
                    $product['name'],
                    $product['is_available'],
                    $product['price'],
                    $product['description'],
                    $product['images'],
                    $product['s_id'],
                    $product['category_id'],
                    $product['category']
                );
                
                if ($insertStmt->execute()) {
                    echo "Product successfully approved and moved.";

                    // Prepare SQL to delete the product from pending_products
                    $deleteSql = "DELETE FROM pending_products WHERE id = ?";
                    $deleteStmt = $conn->prepare($deleteSql);

                    if ($deleteStmt) {
                        $deleteStmt->bind_param("i", $productId);
                        if ($deleteStmt->execute()) {
                            echo "Product successfully deleted from pending.<br>";
                        } else {
                            echo "Error deleting product from pending products: " . $conn->error . "<br>";
                        }
                    } else {
                        echo "Error preparing delete statement: " . $conn->error . "<br>";
                    }
                } else {
                    echo "Error inserting product into products table: " . $conn->error . "<br>";
                }
            } else {
                echo "Error preparing insert statement: " . $conn->error . "<br>";
            }
        } else {
            echo "Product not found in pending products.<br>";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing select statement: " . $conn->error . "<br>";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "No product ID received.<br>";
}
?>