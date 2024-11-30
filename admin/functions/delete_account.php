<?php
include 'C:/xampp/htdocs/qcudigimart/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $user_type = isset($_POST['user_type']) ? $_POST['user_type'] : null;

    // Log incoming data for debugging
    error_log("User ID: $user_id, User Type: $user_type");

    if (empty($user_id) || empty($user_type)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data.']);
        exit();
    }

    // Delete the user account based on user type
    $delete_sql = "";
    if ($user_type === 'Customer') {
        $delete_sql = "DELETE FROM customer WHERE id = ?";
    } else if ($user_type === 'Seller') {
        $delete_sql = "DELETE FROM seller WHERE id = ?";
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user type.']);
        exit();
    }

    // Prepare and execute the SQL statement to delete the user
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'User account has been deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user account.']);
    }

    // Clean up
    $stmt->close();
    $conn->close();
}
?>
