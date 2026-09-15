<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

function sendNotificationConfirmationEmail($to, $subject, $bookName, $userName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'librarymanagementsystem24@gmail.com';
        $mail->Password   = 'ekya ziwu ghaf ndab'; // Use environment variables if possible
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('librarymanagementsystem24@gmail.com', 'Library Management System');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;

        // HTML Body
        $mail->Body = <<<EOD
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #007bff; }
        p { margin: 10px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notification Request Confirmed</h1>
        <p>Dear $userName,</p>
        <p>Thank you for your interest in the book <strong>$bookName</strong>.</p>
        <p>Your notification request has been successfully registered. You will receive an email when this book becomes available.</p>
        <p>If you have any other questions or need further assistance, please feel free to reach out to us.</p>
        <div class="footer">
            <p>Best Regards,</p>
            <p><strong>The Library Management Team</strong></p>
        </div>
    </div>
</body>
</html>
EOD;

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        throw new Exception("Message could not be sent.");
    }
}
?>
