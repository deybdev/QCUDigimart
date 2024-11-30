<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'qcudigimart@gmail.com';
    $mail->Password   = 'hsjd odlk mkqj pubd';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('qcudigimart@gmail.com', 'QCUDigimart');
    $mail->addAddress('davecahilig19@gmail.com', 'Deybb');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'This is a SUBJECT';
    $mail->Body    = '<b>This is an example of a <strong>mail</strong> body</b>';
    $mail->AltBody = 'alt body daw ito';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
