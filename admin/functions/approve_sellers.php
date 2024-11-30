<?php
session_start();
include 'C:/xampp/htdocs/qcudigimart/config/config.php';
include 'C:/xampp/htdocs/qcudigimart/uploads/send_email.php';  // Include the email function

if (isset($_POST['seller_id'])) {
    $seller_id = $_POST['seller_id'];

    // Fetch seller details from `pending_sellers`
    $stmt = $conn->prepare("SELECT first_name, last_name, email, password, store_name, org_type, date_created FROM pending_sellers WHERE id = ?");
    if (!$stmt) {
        die("Error preparing statement for fetching seller: " . $conn->error);
    }

    $stmt->bind_param("i", $seller_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        // Insert the seller into `sellers`
        $stmt_insert = $conn->prepare("INSERT INTO seller (first_name, last_name, email, password, store_name, org_type, date_created) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt_insert) {
            die("Error preparing statement for inserting seller: " . $conn->error);
        }

        $stmt_insert->bind_param("sssssss", $row['first_name'], $row['last_name'], $row['email'], $row['password'], $row['store_name'], $row['org_type'], $row['date_created']);
        
        if ($stmt_insert->execute()) {
            // Send the approval email to the seller
            $recipientEmail = $row['email'];
            $recipientName = $row['first_name'] . ' ' . $row['last_name'];
            $emailStatus = sendSellerApprovalEmail($recipientEmail, $recipientName);

            if ($emailStatus === true) {
                // Delete from `pending_sellers` after insertion
                $stmt_delete = $conn->prepare("DELETE FROM pending_sellers WHERE id = ?");
                if (!$stmt_delete) {
                    die("Error preparing statement for deleting seller: " . $conn->error);
                }

                $stmt_delete->bind_param("i", $seller_id);
                $stmt_delete->execute();
                $_SESSION['message'] = "Seller approved successfully! Approval email sent.";
                $stmt_delete->close();
            } else {
                $_SESSION['message'] = "Seller approved, but there was an issue sending the approval email: " . $emailStatus;
            }
        } else {
            $_SESSION['message'] = "Error approving seller.";
        }
        $stmt_insert->close();
    } else {
        $_SESSION['message'] = "Seller not found.";
    }
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to pending sellers page
header("Location: ../pending-sellers.php");
exit();
?>
