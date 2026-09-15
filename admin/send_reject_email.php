<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

function sendRejectEmail($to, $subject, $bookName, $userName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'librarymanagementsystem24@gmail.com';
        $mail->Password   = ''; // Use environment variables if possible
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
        h1 { color: #d9534f; }
        p { margin: 10px 0; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Request Rejected</h1>
        <p>Hello $userName,</p>
        <p>We regret to inform you that your request for the book <strong>$bookName</strong> has been rejected.</p>
        <p>If you have any questions or need further assistance, please contact us.</p>
        <div class="footer">
            <p>Library Management System</p>
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
