<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

function sendNotification($toEmail, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'shaabansoud05@gmail.com';           // Badilisha na email yako halali
        $mail->Password = 'kief cgwe trzf dlad';         // Badilisha na app password ya Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   // SSL
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('shaabansoud05@gmail.com', 'mwecau_clearance_system');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        // Uncomment if you want to debug
        // echo "Email sent to $toEmail\n";
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        // Uncomment to debug errors
        // echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
