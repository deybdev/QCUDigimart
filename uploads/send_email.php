<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'C:/xampp/htdocs/qcudigimart/vendor/autoload.php';

function sendRegistrationConfirmationEmail($recipientEmail, $recipientName, $verificationCode) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcudigimart@gmail.com';
        $mail->Password = 'hsjd odlk mkqj pubd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('qcudigimart@gmail.com', 'QCUDigimart');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->addEmbeddedImage('../assets/digilogo.png', 'digilogo');

        $mail->isHTML(true);
        $mail->Subject = 'Welcome to QCUDigimart - Verify Your Email';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:digilogo' alt='QCUDigimart Logo' style='max-width: 100%; margin-bottom: 20px;'>
                </div>
                <div style='background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #333;'>Welcome to QCUDigimart!</h2>
                    <p style='font-size: 16px;'>Hi <strong>$recipientName</strong>,</p>
                    <p style='font-size: 16px;'>Welcome to QCUDigimart! Please use the verification code below to complete your registration:</p>
                    <h3 style='color: #0056b3;'>$verificationCode</h3>
                    <p style='font-size: 16px;'>Thank you for joining us!</p>
                    <br>
                    <p style='font-size: 16px;'>Regards,<br>QCUDigimart Team</p>
                </div>
            </div>";
        $mail->AltBody = "Hi $recipientName, Welcome to QCUDigimart! Your verification code is: $verificationCode. Thank you for joining us!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

function sendPasswordResetEmail($recipientEmail, $recipientName, $verificationCode) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcudigimart@gmail.com';
        $mail->Password = 'hsjd odlk mkqj pubd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('qcudigimart@gmail.com', 'QCUDigimart');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->addEmbeddedImage('../assets/digilogo.png', 'digilogo');

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Verification Code';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:digilogo' alt='QCUDigimart Logo' style='max-width: 100%; margin-bottom: 20px;'>
                </div>
                <div style='background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #333;'>Password Reset Request</h2>
                    <p style='font-size: 16px;'>Hi <strong>$recipientName</strong>,</p>
                    <p style='font-size: 16px;'>You requested to reset your password. Please use the verification code below to proceed:</p>
                    <h3 style='color: #0056b3;'>$verificationCode</h3>
                    <p style='font-size: 16px;'>If you did not request a password reset, please ignore this email.</p>
                    <br>
                    <p style='font-size: 16px;'>Regards,<br>QCUDigimart Team</p>
                </div>
            </div>";
        $mail->AltBody = "Hi $recipientName, You requested to reset your password. Your verification code is: $verificationCode. If you did not request this, please ignore this email.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

function sendAdminApprovalEmail($recipientEmail, $recipientName) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcudigimart@gmail.com';
        $mail->Password = 'hsjd odlk mkqj pubd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('qcudigimart@gmail.com', 'QCUDigimart');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->addEmbeddedImage('../assets/digilogo.png', 'digilogo');

        $mail->isHTML(true);
        $mail->Subject = 'Account Pending Approval';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:digilogo' alt='QCUDigimart Logo' style='max-width: 100%; margin-bottom: 20px;'>
                </div>
                <div style='background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #333;'>Account Pending Approval</h2>
                    <p style='font-size: 16px;'>Hi <strong>$recipientName</strong>,</p>
                    <p style='font-size: 16px;'>Thank you for registering with QCUDigimart! Your account is currently under review by our administrators.</p>
                    <p style='font-size: 16px;'>We will notify you as soon as your account is approved.</p>
                    <br>
                    <p style='font-size: 16px;'>Regards,<br>QCUDigimart Team</p>
                </div>
            </div>";
        $mail->AltBody = "Hi $recipientName, Thank you for registering with QCUDigimart! Your account is under review. We will notify you when it is approved.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}

function sendSellerApprovalEmail($recipientEmail, $recipientName) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'qcudigimart@gmail.com'; 
        $mail->Password = 'hsjd odlk mkqj pubd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('qcudigimart@gmail.com', 'QCUDigimart');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->addEmbeddedImage('C:/xampp/htdocs/qcudigimart/assets/digilogo.png', 'digilogo');

        $mail->isHTML(true);
        $mail->Subject = 'Your Seller Account is Approved';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px;'>
                <div style='text-align: center;'>
                    <img src='cid:digilogo' alt='QCUDigimart Logo' style='max-width: 100%; margin-bottom: 20px;'>
                </div>
                <div style='background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);'>
                    <h2 style='color: #333;'>Your Seller Account is Approved</h2>
                    <p style='font-size: 16px;'>Hi <strong>$recipientName</strong>,</p>
                    <p style='font-size: 16px;'>Congratulations! Your seller account with QCUDigimart has been approved and is now active.</p>
                    <p style='font-size: 16px;'>You can start listing your products and begin selling on our platform right away!</p>
                    <p style='font-size: 16px;'>If you have any questions or need assistance, feel free to reach out to our support team.</p>
                    <br>
                    <p style='font-size: 16px;'>We look forward to a successful partnership.</p>
                    <br>
                    <p style='font-size: 16px;'>Best Regards,<br>QCUDigimart Team</p>
                </div>
            </div>";
        $mail->AltBody = "Hi $recipientName, Congratulations! Your seller account with QCUDigimart has been approved and is now active. You can start listing products and selling on our platform.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
