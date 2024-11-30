<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enteredOtp = $_POST['verotp'];

    // Check if email exists in the session
    if (!isset($_SESSION['email'])) {
        $_SESSION['message'] = "Session expired or invalid. Please start the process again.";
        header("Location: forgot.php");
        exit();
    }

    $email = $_SESSION['email'];

    // Check OTP in customer, seller, and pending_sellers tables
    $query = "SELECT verification_code FROM customer WHERE email = ? 
              UNION 
              SELECT verification_code FROM seller WHERE email = ? 
              UNION 
              SELECT verification_code FROM pending_sellers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $email, $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedOtp);
        $stmt->fetch();

        if ($enteredOtp == $storedOtp) {
            $_SESSION['success_message'] = "Verification successful. Please create a new password.";
            header("Location: new_pass.php");
            exit();
        } else {
            $_SESSION['message'] = "Invalid verification code!";
            header("Location: verify_email_pass.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Email or OTP not found.";
        header("Location: verify_email_pass.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
<?php include '../main/header.php'; ?>

<div class="form-container">
    <div class="form-wrapper">
        <div class="form-box">
            <form action="verify_email_pass.php" method="post">
                <div class="form">
                    <h2>Email Verification</h2>

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
                            $infoMessage = htmlspecialchars(addslashes($_SESSION['login_success_message']));
                            echo '<script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        const infoMessage = "' . $infoMessage . '";
                                        showSuccessModal(infoMessage); 
                                    });
                                </script>';
                            unset($_SESSION['login_success_message']);
                        }
                    ?>

                    <?php
                    if (isset($_SESSION['message'])) {
                        echo '<div class="alert-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                        unset($_SESSION['message']);
                    }
                    ?>

                    <div class="note-container">
                        <p>We sent a verification code to your email: <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong></p>
                    </div>
                    <div class="form-element full-width">
                        <label for="otp">Verification Code:</label>
                        <input type="text" id="otp" name="verotp" placeholder="Enter the 6-digit code" required>
                    </div>
                    <div class="form-element">
                        <button class="btn" name="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../main/footer.php'; ?>
</body>
</html>
                    