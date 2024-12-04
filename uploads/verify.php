    <?php
    session_start();
    include '../config/config.php';
    include '../uploads/send_email.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $enteredOtp = $_POST['votp'];

        // Retrieve stored verification code from customer or pending_sellers table
        $stmt = $conn->prepare("SELECT verification_code, 'customer' AS user_type FROM customer WHERE email = ? 
                                UNION
                                SELECT verification_code, 'pending_seller' AS user_type FROM pending_sellers WHERE email = ?");
        $stmt->bind_param("ss", ($_SESSION['email']), ($_SESSION['email']));
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($storedOtp, $userType);
        $stmt->fetch();

        if ($enteredOtp == $storedOtp) {
            // Check if the user is a customer or a pending seller
            if ($userType == 'customer') {
                // Mark the customer as verified
                $updateStmt = $conn->prepare("UPDATE customer SET is_verified = 1 WHERE email = ?");
                $updateStmt->bind_param("s", ($_SESSION['email']));
                $updateStmt->execute();
            } elseif ($userType == 'pending_seller') {
                // Mark the pending seller as verified (waiting for admin approval)
                $updateStmt = $conn->prepare("UPDATE pending_sellers SET is_verified = 1 WHERE email = ?");
                $updateStmt->bind_param("s", ($_SESSION['email']));
                $updateStmt->execute();

                // Send the admin approval email to the user
                $recipientEmail = $_SESSION['email'];
                $recipientName = $_SESSION['first_name']; 
                $emailStatus = sendAdminApprovalEmail($recipientEmail, $recipientName); 
                
                // Show a message that they need to wait for admin approval
                $_SESSION['login_success_message'] = "Your email has been verified! Please wait for admin approval to activate your seller account.";
                header("Location: login.php"); // Redirect back to verification page with a success message
                exit();
            }

            $_SESSION['login_success_message'] = "Your email has been verified! You can now log in.";
            header("Location: login.php");
            exit();
            
        } else {
            $_SESSION['message'] = "Invalid verification code!";
            header("Location: verify.php");
            exit();
        }
    }
    ?>




    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OTP Verification</title>
    </head>
    <body>
    <?php include'../main/header.php'; ?>

        <?php
            if (isset($_SESSION['info_message'])) {
                $infoMessage = htmlspecialchars(addslashes($_SESSION['info_message']));
                echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const infoMessage = "' . $infoMessage . '";
                            showInfoModal(infoMessage); 
                        });
                    </script>';
                unset($_SESSION['info_message']);
            }

            if (isset($_SESSION['success_message'])) {
                $infoMessage = htmlspecialchars(addslashes($_SESSION['success_message']));
                echo '<script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const infoMessage = "' . $infoMessage . '";
                            showSuccessModal(infoMessage); 
                        });
                    </script>';
                unset($_SESSION['success_message']);
            }
        ?>

    <div class="form-container">
        <div class="form-wrapper">
            <div class="form-box">
            <form action="verify.php" method="post">
                <div class="form">
                    <h2>OTP Verification</h2>
                    <?php
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                        unset($_SESSION['message']);
                    }
                    ?>
                    <div class="note-container">
                        <p>We've sent a verification code to your <br> Email - <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong></p>
                    </div>
                    <div class="form-element full-width">
                        <label for="email">Verification Code: </label>
                        <input type="text" id="otp" name="votp" placeholder="Enter the 6 digit OTP">
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