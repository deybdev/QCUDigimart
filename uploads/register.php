<?php
session_start();
include '../config/config.php'; // Database connection
include '../uploads/send_email.php';

if (isset($_POST['register'])) {
    // Fetch form data
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $userType = $_POST['user_type'];
    $storeName = $_POST['store_name'] ?? '';
    $orgType = $_POST['org_type'] ?? '';

    // Check if email already exists
    $sql_check_email = "SELECT id FROM customer WHERE email = ? UNION SELECT id FROM pending_sellers WHERE email = ? UNION SELECT id FROM seller WHERE email = ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    $stmt_check_email->bind_param("sss", $email, $email, $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        $_SESSION['message'] = "This email is already registered.";
        $_SESSION['post_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['message'] = "Password must be at least 8 characters long.";
        $_SESSION['post_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    if ($password !== $cpassword) {
        $_SESSION['message'] = "Passwords do not match.";
        $_SESSION['post_data'] = $_POST;
        header("Location: register.php");
        exit();
    }

    // Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate verification code
    $verificationCode = rand(100000, 999999); // 6-digit code

    // Insert into the appropriate table
    if ($userType == "seller") {
        $sql = "INSERT INTO pending_sellers (first_name, last_name, email, password, store_name, org_type, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $hashedPassword, $storeName, $orgType, $verificationCode);
    } else {
        $sql = "INSERT INTO customer (first_name, last_name, email, password, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $hashedPassword, $verificationCode);
    }

    if ($stmt->execute()) {
        $emailStatus = sendRegistrationConfirmationEmail($email, "$firstName $lastName", $verificationCode);
        if ($emailStatus === true) {
            $_SESSION['email'] = $email;
            header("Location: verify.php");
            exit();
        } else {
            $_SESSION['message'] = "Registration successful, but the verification email could not be sent. $emailStatus";
            $_SESSION['post_data'] = $_POST;
            header("Location: register.php");
            exit();
        }
    }

    // Close statement and connection
    $stmt->close();
    $stmt_check_email->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <style>
       
    </style>

    <?php include '../main/header.php'; ?>
    <div class="form-container">
        <div class="form-wrapper">
            <div class="form-box">
                <form action="register.php" method="post" id="registrationForm">
                    <div class="form">
                        <h2>REGISTER</h2>
                        <?php
                        if (isset($_SESSION['message'])) {
                            echo '<div class="alert-message">
                                <p>' . htmlspecialchars($_SESSION['message']) . '</p>
                            </div>';
                            unset($_SESSION['message']);
                        }

                        $postData = $_SESSION['post_data'] ?? [];
                        unset($_SESSION['post_data']);
                        ?>
                        <div class="form-element half-width">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" 
                                value="<?php echo htmlspecialchars($postData['first-name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-element half-width">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last-name" 
                                value="<?php echo htmlspecialchars($postData['last-name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                value="<?php echo htmlspecialchars($postData['email'] ?? ''); ?>" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="password">Create Password</label>
                            <input type="password" id="password" name="password" 
                                value="<?php echo htmlspecialchars($postData['password'] ?? ''); ?>" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="cpassword">Confirm Password</label>
                            <input type="password" id="cpassword" name="cpassword" 
                                value="<?php echo htmlspecialchars($postData['cpassword'] ?? ''); ?>" required>
                        </div>
                        <div class="form-element full-width">
                            <label for="user_type">User Type</label>
                            <select name="user_type" id="user_type" required>
                                <option value="customer" <?php echo ($postData['user_type'] ?? '') === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="seller" <?php echo ($postData['user_type'] ?? '') === 'seller' ? 'selected' : ''; ?>>Seller</option>
                            </select>
                        </div>
                        <div class="form-element full-width" id="store_name_container" style="<?php echo ($postData['user_type'] ?? '') === 'seller' ? '' : 'display: none;'; ?>">
                            <label for="store_name">Store Name</label>
                            <input type="text" id="store_name" name="store_name" 
                                value="<?php echo htmlspecialchars($postData['store_name'] ?? ''); ?>" 
                                placeholder="Only for sellers" <?php echo ($postData['user_type'] ?? '') === 'seller' ? 'required' : ''; ?>>
                        </div>
                        <div class="form-element full-width" id="org_type_container" style="<?php echo ($postData['user_type'] ?? '') === 'seller' ? '' : 'display: none;'; ?>">
                            <label for="org_type">Organization Type</label>
                            <select name="org_type" id="org_type" <?php echo ($postData['user_type'] ?? '') === 'seller' ? 'required' : ''; ?>>
                                <option value="" <?php echo ($postData['org_type'] ?? '') === '' ? 'selected' : ''; ?>>-</option>
                                <option value="market" <?php echo ($postData['org_type'] ?? '') === 'market' ? 'selected' : ''; ?>>Org's Market</option>
                                <option value="enterprise" <?php echo ($postData['org_type'] ?? '') === 'enterprise' ? 'selected' : ''; ?>>Entrep's Enterprise</option>
                                <option value="cafeteria" <?php echo ($postData['org_type'] ?? '') === 'cafeteria' ? 'selected' : ''; ?>>Cafeteria</option>
                                <option value="coop" <?php echo ($postData['org_type'] ?? '') === 'coop' ? 'selected' : ''; ?>>CO-OP</option>
                                <option value="freelance" <?php echo ($postData['org_type'] ?? '') === 'freelance' ? 'selected' : ''; ?>>Freelance</option>
                            </select>
                        </div>
                        <div class="form-element">
                            <input type="checkbox" id="terms" required>
                            <label for="terms">
                                I agree to the <a href="#" id="termsLink">Terms and Conditions</a>
                            </label>
                        </div>
                        <div class="form-element">
                            <button class="btn" name="register">Register</button>
                        </div>
                        <div class="form-element">
                            <hr>
                            <p>Already have an account? <a href="login.php">Login</a></p>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
        <div id="termsModal" class="terms-modal">
            <div class="terms-modal-content">
                <span class="close">&times;</span>
                <h2>Terms and Conditions</h2>
                <div class="terms-content">
                    <p>
                        <strong>1. Acceptance of Terms</strong><br>
                        By accessing or using this website, you agree to be bound by these Terms and Conditions. If you do not agree to these terms, please refrain from using the website.
                    </p><br>
                    <p>
                        <strong>2. Website Use</strong><br>
                        <strong>● Permitted Use:</strong> You may use the website for personal, non-commercial purposes. Any commercial use of the website or its content is strictly prohibited without prior written consent.<br>
                        <strong>● Prohibited Use:</strong> You may not:
                        <ul>
                            Use the website in a manner that violates any applicable laws or regulations.
                            Reproduce, modify, distribute, or publicly display the website or its content without permission.
                            Attempt to gain unauthorized access to the website or its systems.
                        </ul>
                    </p><br>
                    <p>
                        <strong>3. Intellectual Property</strong><br>
                        <strong>● Ownership:</strong> All content on the website, including text, graphics, logos, and trademarks, is the property of QCU Digimart and is protected by copyright and other intellectual property laws.<br>
                        <strong>● Limited License:</strong> You are granted a limited, non-exclusive, revocable license to access and use the website for personal, non-commercial purposes.
                    </p><br>
                    <p>
                        <strong>4. Disclaimer of Warranties</strong><br>
                        <strong>● No Warranties:</strong> The website is provided on an "as is" basis without warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.<br>
                        <strong>● Accuracy of Information:</strong> While we strive to ensure the accuracy of the information on the website, we do not guarantee the completeness or reliability of such information.
                    </p><br>
                    <p>
                        <strong>5. Limitation of Liability</strong><br>
                        <strong>● No Liability:</strong> In no event shall QCU Digimart be liable for any damages, including but not limited to direct, indirect, incidental, special, or consequential damages, arising out of or in connection with your use of the website or its content.
                    </p>
                    <div class="modal-footer">
                        <button id="closeModalBottom" class="btn-close btn">Close</button>
                    </div>
                </div>
            </div>
        </div>


    <?php include '../main/footer.php'; ?>

    <script>
        
        document.getElementById("user_type").addEventListener("change", function() {
            var userType = this.value;
            var storeContainer = document.getElementById("store_name_container");
            var orgContainer = document.getElementById("org_type_container");
            var storeInput = document.getElementById("store_name");
            var orgInput = document.getElementById("org_type");

            if (userType === "seller") {
                storeContainer.style.display = "block";
                storeInput.required = true;
                orgContainer.style.display = "block";
                orgInput.required = true;
            } else {
                storeContainer.style.display = "none";
                storeInput.required = false;
                storeInput.value = '';
                orgContainer.style.display = "none";
                orgInput.required = false;
                orgInput.value = '';
            }
        });

        // Get the modal and elements
        var modal = document.getElementById("termsModal");
        var btn = document.getElementById("termsLink");
        const closeButtonBottom = document.getElementById("closeModalBottom");
        var span = document.getElementsByClassName("close")[0];

        // Show the modal when the user clicks the Terms link
        btn.onclick = function(event) {
            event.preventDefault(); // Prevent default link behavior
            modal.style.display = "block";
        };

        closeButtonBottom.addEventListener("click", () => {
            modal.style.display = "none"; // Hide the modal
        });

        // Hide the modal when the user clicks the close button
        span.onclick = function() {
            modal.style.display = "none";
        };

        // Hide the modal when the user clicks outside of the modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>
</html>
