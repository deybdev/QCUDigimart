<?php
include '../config/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $productId = $data['id'];

    // Prepare SQL to toggle is_available
    $stmt = $conn->prepare("
        UPDATE product
        SET is_available = CASE WHEN is_available = 1 THEN 0 ELSE 1 END 
        WHERE id = ?
    ");

    if ($stmt === false) {
        error_log("SQL Error: " . $conn->error);
        echo json_encode(['success' => false, 'message' => $conn->error]);
        exit;
    }

    $stmt->bind_param('i', $productId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product availability toggled successfully.']);
    } else {
        error_log("Execution Error: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
