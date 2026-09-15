<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

// send_email.php

function sendReturnEmail($to, $subject, $bookName, $returnDate, $userName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'librarymanagementsystem24@gmail.com';
        $mail->Password   = 'your_password'; // Replace with the actual password or use environment variables
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
        h1 { color: #0056b3; }
        p { margin: 10px 0; }
        .highlight { background-color: #f9f9f9; padding: 10px; border-radius: 3px; }
        .footer { font-size: 12px; color: #777; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Book Now Available</h1>
        <p>Hello $userName,</p>
        <p>The book <strong>$bookName</strong> you were interested in is now available for request.</p>
        <p>The book was returned on <strong>$returnDate</strong>.</p>
        <p>Please log in to request the book if needed.</p>
        <div class="footer">
            <p>If you have any questions, please contact us.</p>
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

