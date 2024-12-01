<?php
include '../config/config.php';
session_start();

// Check if the seller is logged in
if (!isset($_SESSION['seller_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

$seller_id = $_SESSION['seller_id'];

// Check if the request method is POST and if the required data is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $product_id = $data['id'];
        $status = $data['status'];
        $table = '';

        // Determine the table based on the status
        switch ($status) {
            case 'Rejected':
                $table = 'rejected_products';
                break;
            case 'Pending':
                $table = 'pending_products';
                break;
            case 'Active':
            case 'Out of Stock':
                $table = 'product';
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid product status.']);
                exit;
        }

        // Delete the product from the determined table
        $sql = "DELETE FROM $table WHERE id = ? AND s_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ii", $product_id, $seller_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Product removed successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to remove product.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error preparing statement.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>
