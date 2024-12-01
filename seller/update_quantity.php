<?php
include '../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['id'] ?? null;
    $quantity = $data['quantity'] ?? null;
    $sellerId = $_SESSION['seller_id'];

    if ($productId && $quantity && $quantity > 0) {
        $sql = "UPDATE product SET quantity = quantity + ? WHERE id = ? AND s_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iii', $quantity, $productId, $sellerId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product quantity updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
}
?>
