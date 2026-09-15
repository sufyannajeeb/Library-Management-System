<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function sendNotifyEmail($to, $subject, $bookName, $userName) {
    // Debugging: Print input parameters
    echo "To: $to<br>";
    echo "Subject: $subject<br>";
    echo "Book Name: $bookName<br>";
    echo "User Name: $userName<br>";

    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'librarymanagementsystem24@gmail.com';    // SMTP username
        $mail->Password   = 'ekya ziwu ghaf ndab';    // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;                    // TCP port to connect to

        // Recipients
        $mail->setFrom('librarymanagementsystem24@gmail.com', 'Library');
        $mail->addAddress($to, $userName);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "
            <html>
            <head>
                <title>$subject</title>
                <style>
                    body {
                        font-family: 'Verdana', sans-serif;
                        color: #f5f5f5;
                        background-color: #1c1c1c;
                        margin: 0;
                        padding: 0;
                        background-image: url('https://www.transparenttextures.com/patterns/gplay.png');
                        background-size: cover;
                    }
                    .container {
                        width: 100%;
                        max-width: 600px;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #333;
                        border-radius: 10px;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                        border: 2px solid #e0b108;
                    }
                    h1 {
                        color: #e0b108;
                        font-size: 24px;
                    }
                    p {
                        font-size: 16px;
                        line-height: 1.7;
                        margin-bottom: 15px;
                        color: #f5f5f5;
                    }
                    .button {
                        display: inline-block;
                        padding: 12px 25px;
                        font-size: 16px;
                        color: #000;
                        background-color: #87CEFA;
                        border-radius: 25px;
                        text-decoration: none;
                        margin-top: 10px;
                        font-weight: bold;
                    }
                    .button:hover {
                        background-color: #00BFFF;
                    }
                    .footer {
                        margin-top: 25px;
                        font-size: 14px;
                        color: #b3b3b3;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>$subject</h1>
                    <p>Dear $userName,</p>
                    <p>The book <strong>$bookName</strong> that you requested to be notified about is now available.</p>
                    <p>You may now proceed to request and collect the book.</p>
                    <a href='#' class='button'>Request Book Now</a>
                    <p class='footer'>Thank you for using our library services!</p>
                </div>
            </body>
            <script>
                document.querySelector('.button').addEventListener('click', function(event) {
                    event.preventDefault();
                    alert('This would normally lead to the book request page.');
                });
            </script>
            </html>
        ";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}



?>
