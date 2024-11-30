<?php
include "../config/config.php";
session_start();

$current_user_id = $_SESSION['customer_id'] ?? $_SESSION['seller_id'] ?? null;
if (!$current_user_id) {
    die(json_encode(["error" => "Unauthorized"]));
}

$receiver_id = $_GET['receiver_id'] ?? null;
if (!$receiver_id) {
    die(json_encode(["error" => "Receiver ID is required"]));
}

$messages = [];
$query = "
    SELECT m.sender_id, m.receiver_id, m.m_content, m.date_created,
           -- Fetch correct profile images for sender and receiver
           IF(m.sender_id = ?, c.profile_image, s.store_profile) AS sender_profile,
           IF(m.receiver_id = ?, s.store_profile, c.profile_image) AS receiver_profile
    FROM message m
    LEFT JOIN customer c ON m.sender_id = c.id OR m.receiver_id = c.id
    LEFT JOIN seller s ON m.sender_id = s.id OR m.receiver_id = s.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.date_created ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiiiii", $current_user_id, $current_user_id, $current_user_id, $receiver_id, $receiver_id, $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
