<?php
include "../config/config.php";
session_start();

// Validate user session and type
if (isset($_SESSION['customer_id'])) {
    $current_user_id = $_SESSION['customer_id'];
    $user_type = 'customer';
} elseif (isset($_SESSION['seller_id'])) {
    $current_user_id = $_SESSION['seller_id'];
    $user_type = 'seller';
} else {
    die(json_encode(['error' => 'Not logged in']));
}

// Simplified recent chats query
$recent_chats_query = "
    SELECT 
        IF(sender_id = ?, receiver_id, sender_id) AS chat_partner_id,
        MAX(date_created) AS last_message_time,
        SUBSTRING_INDEX(GROUP_CONCAT(m_content ORDER BY date_created DESC), ',', 1) AS last_message
    FROM message
    WHERE sender_id = ? OR receiver_id = ?
    GROUP BY chat_partner_id
    ORDER BY last_message_time DESC
    LIMIT 20
";

$stmt = $conn->prepare($recent_chats_query);
$stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$recent_chats_result = $stmt->get_result();

$recent_chats = [];
while ($chat = $recent_chats_result->fetch_assoc()) {
    // Fetch partner details separately
    $partner_id = $chat['chat_partner_id'];
    
    // Try to get seller details
    $partner_query = "
        SELECT store_name AS name, store_profile AS profile_image FROM seller WHERE id = ?
        UNION
        SELECT CONCAT(first_name, ' ', last_name) AS name, profile_image FROM customer WHERE id = ?
    ";
    $partner_stmt = $conn->prepare($partner_query);
    $partner_stmt->bind_param("ii", $partner_id, $partner_id);
    $partner_stmt->execute();
    $partner_result = $partner_stmt->get_result()->fetch_assoc();

    $chat['name'] = $partner_result['name'] ?? 'Unknown';
    $chat['profile_image'] = $partner_result['profile_image'] ?? '../assets/default-profile.png';

    $recent_chats[] = $chat;
}

echo json_encode($recent_chats);
?>