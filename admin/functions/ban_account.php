<?php
include 'C:/xampp/htdocs/qcudigimart/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

    // Validate input
    if (empty($user_id) || empty($user_type)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
        exit();
    }

    // Determine the table to query based on user type
    $table = $user_type === 'Customer' ? 'customer' : ($user_type === 'Seller' ? 'seller' : null);

    if (!$table) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user type.']);
        exit();
    }

    // Check the current status of the user
    $status_check_sql = "SELECT status FROM $table WHERE id = ?";
    $stmt = $conn->prepare($status_check_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($current_status);
    $stmt->fetch();

    if (!$stmt->num_rows) {
        echo json_encode(['status' => 'error', 'message' => 'User not found.']);
        exit();
    }

    $stmt->close();

    // Toggle between banning and unbanning
    if (strtolower($current_status) === 'banned') {
        // Unban the user
        $unban_sql = "UPDATE $table SET status = 'active', suspend_until = NULL WHERE id = ?";
        $stmt = $conn->prepare($unban_sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User has been unbanned successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to unban user.']);
        }
    } else {
        // Ban the user
        $ban_sql = "UPDATE $table SET status = 'banned', suspend_until = NULL WHERE id = ?";
        $stmt = $conn->prepare($ban_sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User has been banned successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to ban user.']);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
