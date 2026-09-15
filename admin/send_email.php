<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

function sendStyledEmail($to, $subject, $bookName, $returnDate, $userName) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'librarymanagementsystem24@gmail.com';
        $mail->Password   = 'ekya ziwu ghaf ndab';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('librarymanagementsystem24@gmail.com', 'Library Management');
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
        body { 
            font-family: Arial, sans-serif; 
            color: #333; 
            background-color: #e8f7ff; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 600px; 
            margin: 20px auto; 
            padding: 20px; 
            background-color: #fff; 
            border: 1px solid #ccc; 
            border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 { 
            color: #f0ad4e; 
            background-color: #d9edf7; 
            padding: 10px; 
            border-radius: 5px; 
            text-align: center;
            position: relative;
        }
        h1:before {
            content: '📚';
            position: absolute;
            left: 10px;
            top: 10px;
        }
        h1:after {
            content: '📚';
            position: absolute;
            right: 10px;
            top: 10px;
        }
        p { 
            margin: 10px 0; 
            color: #337ab7;
        }
        .highlight { 
            background-color: #fefbd8; 
            padding: 10px; 
            border-radius: 3px; 
            border-left: 5px solid #f0ad4e;
        }
        .footer { 
            font-size: 12px; 
            color: #777; 
            text-align: center; 
            margin-top: 20px; 
        }
        .animate:hover {
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="animate">Book Request Approved</h1>
        <p>Hello $userName,</p>
        <p>Your request for the book <strong>$bookName</strong> has been approved.</p>
        <p class="highlight"><strong>Return Date:</strong> $returnDate</p>
        <p>Please collect the book within one day from the approval date.</p>
        <div class="footer">
            <p>If you have any questions, please contact us.</p>
            <p>Library Management System</p>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('h1').addEventListener('mouseover', function() {
            this.style.backgroundColor = '#fefbd8';
        });
        document.querySelector('h1').addEventListener('mouseout', function() {
            this.style.backgroundColor = '#d9edf7';
        });
    });
</script>
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
