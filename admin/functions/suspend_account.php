<?php
include 'C:/xampp/htdocs/qcudigimart/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'] ?? null;
    $userType = $_POST['user_type'] ?? null;
    $action = $_POST['action'] ?? null;
    $suspendUntil = $_POST['suspend_until'] ?? null;

    // Validate inputs
    if (!is_numeric($userId) || empty($userType) || empty($action)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
        exit;
    }

    $table = $userType === 'Seller' ? 'seller' : 'customer';

    if ($action === 'suspend') {
        // Validate date format for suspension
        if (empty($suspendUntil) || !DateTime::createFromFormat('Y-m-d', $suspendUntil)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid suspension date.']);
            exit;
        }

        // Suspend user
        $sql = "UPDATE $table SET status = 'suspended', suspend_until = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $suspendUntil, $userId);
    } elseif ($action === 'unsuspend') {
        // Unsuspend user
        $sql = "UPDATE $table SET status = 'active', suspend_until = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        exit;
    }

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => ucfirst($action) . ' operation successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update the account.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
