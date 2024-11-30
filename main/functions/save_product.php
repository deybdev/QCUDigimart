<?php
// Enable error reporting for debugging (remove or disable in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'C:/xampp/htdocs/qcudigimart/config/config.php';

try {

    session_start();
    if (!isset($_SESSION['customer_id'])) {
        throw new Exception("Please login as CUSTOMER to save this products.");
    }

    // Retrieve variables
    $product_id = intval($_POST['product_id']); // Ensure $product_id is an integer
    $user_id = intval($_SESSION['customer_id']);

    // Check if the product is already saved
    $checkQuery = "SELECT id FROM saved_products WHERE user_id = ? AND product_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('ii', $user_id, $product_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Remove product if already saved
        $deleteQuery = "DELETE FROM saved_products WHERE user_id = ? AND product_id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('ii', $user_id, $product_id);
        if ($deleteStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Product removed from saved list.",
                "saved" => false
            ]);
        } else {
            throw new Exception("Failed to remove product.");
        }
    } else {
        // Save product if not already saved
        $saveQuery = "INSERT INTO saved_products (user_id, product_id) VALUES (?, ?)";
        $saveStmt = $conn->prepare($saveQuery);
        $saveStmt->bind_param('ii', $user_id, $product_id);
        if ($saveStmt->execute()) {
            echo json_encode([
                "success" => true,
                "message" => "Product saved successfully.",
                "saved" => true
            ]);
        } else {
            throw new Exception("Failed to save product.");
        }
    }
} catch (Exception $e) {
    // Log error for server-side debugging
    error_log("Error: " . $e->getMessage());

    // Return error as JSON response
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
?>
