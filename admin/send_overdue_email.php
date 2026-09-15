<?php
// send_overdue_email.php

/**
 * Send an overdue notification email to the user.
 *
 * @param string $to Email address of the recipient.
 * @param string $subject Subject of the email.
 * @param string $bookName Name of the book.
 * @param string $userName Name of the user.
 */
function sendOverdueEmail($to, $subject, $bookName, $userName) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    $message = "
    <html>
    <head>
        <title>$subject</title>
    </head>
    <body>
        <p>Dear $userName,</p>
        <p>Your request for the book <strong>$bookName</strong> is now overdue.</p>
        <p>Please return the book as soon as possible to avoid any penalties.</p>
        <p>Thank you!</p>
    </body>
    </html>
    ";

    mail($to, $subject, $message, $headers);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Overdue Notification</title>
</head>
<body>
    <p>Dear {{userName}},</p>
    <p>Your request for the book <strong>{{bookName}}</strong> is now overdue.</p>
    <p>Please return the book as soon as possible to avoid any penalties.</p>
    <p>Thank you!</p>
</body>
</html>

