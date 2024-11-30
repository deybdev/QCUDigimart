<?php
session_start();
include '../config/config.php'; // Database connection

// LOGIN FUNCTION
function loginUser($email, $password, $conn) {
    $email = mysqli_real_escape_string($conn, $email);
    
    $check_customers = mysqli_query($conn, "SELECT * FROM `customer` WHERE email ='$email'") or die('Query failed: ' . mysqli_error($conn));
    $check_sellers = mysqli_query($conn, "SELECT * FROM `seller` WHERE email ='$email'") or die('Query failed: ' . mysqli_error($conn));
    $check_admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE a_email ='$email'") or die('Query failed: ' . mysqli_error($conn));
    $check_pending_sellers = mysqli_query($conn, "SELECT * FROM `pending_sellers` WHERE email ='$email'") or die('Query failed: ' . mysqli_error($conn));

    $currentDate = date('Y-m-d');

    if (mysqli_num_rows($check_customers) > 0) {
        $row = mysqli_fetch_assoc($check_customers);
    
        // Verify password first
        if (!password_verify($password, $row['password'])) {
            return ['status' => false, 'message' => 'Incorrect password!'];
        }
    
        // Check account status
        if ($row['status'] === 'banned') {
            $_SESSION['login_info_message'] = 'Your account is banned. Contact support for assistance.';
            return ['status' => false];
        } elseif ($row['status'] === 'suspended') {
            if ($row['suspend_until'] > $currentDate) {
                $_SESSION['login_info_message'] = 'Your account is suspended until ' . date('F j, Y', strtotime($row['suspend_until']));
                return ['status' => false];
            } else {
                $updateStatus = mysqli_query($conn, "UPDATE `customer` SET status = 'active' WHERE id = {$row['id']}");
                $row['status'] = 'active';
            }
        }
    
        // Check if verified
        if ($row['is_verified'] == 0) {
            $_SESSION['info_message'] = 'Please verify your email using the verification code sent to your email.';
            $_SESSION['email'] = $email;
            header("Location: ../uploads/verify.php");
            exit;
        }
    
        // Set session variables
        $_SESSION['customer_id'] = $row['id'];
        $_SESSION['customer_first_name'] = $row['first_name'];
        $_SESSION['customer_last_name'] = $row['last_name'];
        $_SESSION['customer_email'] = $row['email'];
        $_SESSION['profile_image'] = !empty($row['profile_image']) ? $row['profile_image'] : 'profile-placeholder.png';
        $_SESSION['success_message'] = "Welcome, {$row['first_name']} {$row['last_name']}!";
        return ['status' => true, 'user_type' => 'customer'];
    }
     elseif (mysqli_num_rows($check_sellers) > 0) {
        $row = mysqli_fetch_assoc($check_sellers);
        
        // Check account status
        if ($row['status'] === 'banned') {
            $_SESSION['login_info_message'] = 'Your account is banned. Contact support for assistance.';
            return ['status' => false];
        } elseif ($row['status'] === 'suspended') {
            if ($row['suspend_until'] > $currentDate) {
                $_SESSION['login_info_message'] = 'Your account is suspended until ' . $row['suspend_until'];
                return ['status' => false];
            } else {
                $updateStatus = mysqli_query($conn, "UPDATE `seller` SET status = 'active', suspend_until = 0 WHERE id = {$row['id']}");
                $row['status'] = 'active';
            }
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['seller_first_name'] = $row['first_name'];
            $_SESSION['seller_last_name'] = $row['last_name'];
            $_SESSION['seller_email'] = $row['email'];
            $_SESSION['seller_id'] = $row['id'];
            $_SESSION['seller_profile'] = $row['store_profile'];
            $_SESSION['store_name'] = $row['store_name'];
            $_SESSION['success_message'] = "Welcome, {$row['first_name']} {$row['last_name']}!";
            return ['status' => true, 'user_type' => 'seller'];
        } else {
            return ['status' => false, 'message' => 'Incorrect password!'];
        }
    } elseif (mysqli_num_rows($check_admin) > 0) {
        $row = mysqli_fetch_assoc($check_admin);
        if (password_verify($password, $row['a_password'])) {
            $_SESSION['admin_id'] = $row['a_id'];
            $_SESSION['admin_email'] = $row['a_email'];
            return ['status' => true, 'user_type' => 'admin'];
        } else {
            return ['status' => false, 'message' => 'Incorrect password!'];
        }
    } elseif (mysqli_num_rows($check_pending_sellers) > 0) {
        // Check if the password is correct for pending seller
        $row = mysqli_fetch_assoc($check_pending_sellers);
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Check if verified
        if ($row['is_verified'] == 0) {
            $_SESSION['info_message'] = 'Your seller account is not verified. Please check your email for the verification code.';
            header("Location: ../uploads/verify.php");
            exit;
        }else
            $_SESSION['login_info_message'] = 'Your seller account is pending approval. Please wait for confirmation.';
            return ['status' => false];
        } else {
            return ['status' => false, 'message' => 'Incorrect password!'];
        }
    }

    return ['status' => false, 'message' => 'User not found!'];
}

// Handle login submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginResult = loginUser($email, $password, $conn);

    if ($loginResult['status']) {
        switch ($loginResult['user_type']) {
            case 'customer':
                header("Location: ../main/main-home.php");
                exit;
            case 'seller':
                header("Location: ../main/main-home.php");
                exit;
            case 'admin':
                header("Location: ../admin/dashboard.php");
                exit;
        }
    } else {
        $_SESSION['message'] = $loginResult['message'];
        header("Location: ../uploads/login.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php include '../main/header.php'; ?>
    <?php
    if (isset($_SESSION['login_info_message'])) {
        $infoMessage = htmlspecialchars(addslashes($_SESSION['login_info_message']));
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    const infoMessage = "' . $infoMessage . '";
                    showInfoModal(infoMessage); 
                });
              </script>';
        unset($_SESSION['login_info_message']);
    }

    if (isset($_SESSION['login_success_message'])) {
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

    <div class="form-container">
        <div class="form-wrapper">
            <div class="form-box">
                <form action="login.php" method="post">
                    <div class="form">
                        <h2>LOGIN</h2>
                        <div class="form-element full-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-element">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember Password</label>
                            <a href="forgot.php" class="forgot">Forgot Password</a>
                        </div>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo '<div class="alert-message"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
                            unset($_SESSION['message']);
                        }
                        ?>
                        <div class="form-element">
                            <button class="btn" name="login">Login</button>
                        </div>
                        <div class="form-element">
                            <hr>
                            <p>Don't have an account? <a href="../uploads/register.php">Register</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include '../main/footer.php'; ?>
</body>
</html>
