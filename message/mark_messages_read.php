    <?php
    session_start();
    include '../config/config.php';

    $response = ['success' => false];

    // Determine current user
    if (isset($_SESSION['seller_id'])) {
        $current_user_id = $_SESSION['seller_id'];
    } elseif (isset($_SESSION['customer_id'])) {
        $current_user_id = $_SESSION['customer_id'];
    } else {
        echo json_encode($response);
        exit;
    }

    // Only update receiver's unread messages
    $update_query = "UPDATE message SET is_read = 1 WHERE receiver_id = ? AND is_read = 0";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("i", $current_user_id);


    if ($stmt->execute()) {
        $response['success'] = true;
    }

    echo json_encode($response);
    $stmt->close();
    $conn->close();