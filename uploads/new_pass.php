<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_pass'];
    $confirmPassword = $_POST['cnew_pass'];

    // Check if email exists in the session
    if (!isset($_SESSION['email'])) {
        $_SESSION['message'] = "Session expired or invalid. Please start the process again.";
        header("Location: forgot.php");
        exit();
    }

    $email = $_SESSION['email'];

    // Validate input
    if (empty($newPassword) || empty($confirmPassword)) {
        $_SESSION['message'] = "All fields are required.";
        header("Location: new_pass.php");
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        $_SESSION['message'] = "Passwords do not match.";
        header("Location: new_pass.php");
        exit();
    }

    if (strlen($newPassword) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters long.";
        header("Location: new_pass.php");
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in customer or seller table
    $queries = [
        "UPDATE customer SET password = ? WHERE email = ?",
        "UPDATE seller SET password = ? WHERE email = ?",
        "UPDATE pending_sellers SET password = ? WHERE email = ?"
    ];

    foreach ($queries as $query) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $hashedPassword, $email);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $_SESSION['login_success_message'] = "Your password has been updated successfully! You can now log in.";
            unset($_SESSION['email']); // Clear email from session
            header("Location: login.php");
            exit();
        }
    }

    // If no table is updated
    $_SESSION['message'] = "An error occurred. Please try again.";
    header("Location: new_pass.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
</head>
<body>
    <?php include '../main/header.php'; ?>

    <div class="form-container">
        <div class="form-wrapper">
            <div class="form-box">
                <form action="new_pass.php" method="post">
                    <div class="form">
                        <h2>New Password</h2>
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

                        <?php
                        if (isset($_SESSION['message'])) {
                            echo '<div class="alert-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                            unset($_SESSION['message']);
                        }
                        ?>
                        <div class="note-container">
                            <p>Please create your new password.</p>
                        </div>
                        <div class="form-element full-width">
                            <label for="new_pass">Create your new password:</label>
                            <input type="password" id="new_pass" name="new_pass" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="cnew_pass">Re-enter your new password:</label>
                            <input type="password" id="cnew_pass" name="cnew_pass" required>
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
