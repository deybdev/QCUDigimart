<?php
session_start();
include 'C:/xampp/htdocs/qcudigimart/config/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $report_id = $_POST['report_id'];
    $admin_comment = $_POST['admin_comment'];

    // Prepare and execute the query to update the report status
    $query = "UPDATE reports SET status = 'resolved', admin_comment = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $admin_comment, $report_id);
    
    if ($stmt->execute()) {
        // Return a JSON response on success
        echo json_encode(['status' => 'success']);
    } else {
        // Return a JSON response on failure
        echo json_encode(['status' => 'error']);
    }
    $stmt->close();
    $conn->close();
}
?>
