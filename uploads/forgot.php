<?php
session_start();
include '../config/config.php';
include '../uploads/send_email.php';

// Function to generate a random verification code
function generateVerificationCode($length = 6) {
    return rand(pow(10, $length - 1), pow(10, $length) - 1);
}

// Handle form submission for forgot password
if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    // Check if the email exists in the customer, seller, or pending_sellers tables
    $query = "SELECT id, first_name, last_name, verification_code, email, 'customer' AS user_type 
          FROM `customer` WHERE email = ? 
          UNION 
          SELECT id, first_name, last_name, verification_code, email, 'seller' AS user_type 
          FROM `seller` WHERE email = ? 
          UNION 
          SELECT id, first_name, last_name, verification_code, email, 'pending_sellers' AS user_type 
          FROM `pending_sellers` WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $verificationCode = generateVerificationCode();

        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['email'] = $email;

        $row = $result->fetch_assoc();
        $recipientName = $row['first_name'];
        $userId = $row['id'];
        $userType = $row['user_type'];

        // Update the verification code for the respective table
        if ($userType == 'customer') {
            $updateQuery = "UPDATE `customer` SET verification_code = ? WHERE id = ?";
        } elseif ($userType == 'seller') {
            $updateQuery = "UPDATE `seller` SET verification_code = ? WHERE id = ?";
        } elseif ($userType == 'pending_sellers') {
            $updateQuery = "UPDATE `pending_sellers` SET verification_code = ? WHERE id = ?";
        }

        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('si', $verificationCode, $userId);
        
        if ($updateStmt->execute()) {
            // Send the verification code to the user's email
            if (sendPasswordResetEmail($email, $recipientName, $verificationCode)) {
                $_SESSION['info_message'] = "A verification code has been sent to your email to reset your password. Please check your inbox.";
                header("Location: verify_email_pass.php");
                exit;
            } else {
                $_SESSION['message'] = "There was an error sending the verification email. Please try again.";
            }
        } else {
            $_SESSION['message'] = "There was an error updating the verification code in the database.";
        }
    } else {
        $_SESSION['message'] = "No account found with that email address.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>

<?php include'../main/header.php'; ?>

<div class="form-container">
    <div class="form-wrapper">
        <div class="form-box">
            <form action="forgot.php" method="post">
                <div class="form">
                <h2>Forgot Password</h2>
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div class="alert-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                    unset($_SESSION['message']);
                }
                ?>
                <div class="form-element full-width">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address">
                </div>
                <div class="form-element">
                    <button class="btn" name="submit">Submit</button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include'../main/footer.php'; ?>
    
</body>
</html>
