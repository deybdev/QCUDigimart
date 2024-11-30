<?php
session_start();
include 'C:/xampp/htdocs/qcudigimart/config/config.php';

if (isset($_POST['seller_id'])) {
    $seller_id = $_POST['seller_id'];
    
    // Start a transaction to ensure all queries succeed together
    $conn->begin_transaction();
    
    try {
        // Retrieve seller name from `pending_sellers` before deletion
        $select_sql = "SELECT first_name, last_name FROM pending_sellers WHERE id = ? ORDER BY date_created DESC";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param("i", $seller_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        $seller = $result->fetch_assoc();
        $select_stmt->close();
        
        if ($seller) {
            $seller_name = $seller['first_name'] . ' ' . $seller['last_name'];
            
            // Delete seller from `pending_sellers`
            $delete_sql = "DELETE FROM pending_sellers WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $seller_id);
            $delete_stmt->execute();
            $delete_stmt->close();
            
            // Insert rejected seller into `rejected_sellers` with name and date
            $insert_sql = "INSERT INTO rejected_sellers (id, name, date_rejected) VALUES (?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("is", $seller_id, $seller_name);
            $insert_stmt->execute();
            $insert_stmt->close();
            
            // Commit the transaction
            $conn->commit();
            
            $_SESSION['message'] = "Seller rejected successfully.";
        } else {
            $_SESSION['message'] = "Seller not found.";
        }
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();
        $_SESSION['message'] = "Failed to reject seller.";
    }
    
    $conn->close();
} else {
    $_SESSION['message'] = "Invalid request.";
}

// Redirect back to pending sellers page
header("Location: ../pending-sellers.php");
exit();
?>
