<?php
ob_start();
session_start();

// Include database configuration file
require('config.php'); // Ensure this includes your database connection details

// Include PHPMailer library files
require 'D:\PHPMailer-master\PHPMailer-master\src\Exception.php';
require 'D:\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require 'D:\PHPMailer-master\PHPMailer-master\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Sanitize and validate email input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Escape email for database query
        $email = mysqli_real_escape_string($connection, $email);

        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Prepare insert statement
        $insert_query = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        $insert_stmt = mysqli_prepare($connection, $insert_query);
        if ($insert_stmt) {
            // Bind parameters
            mysqli_stmt_bind_param($insert_stmt, "sss", $email, $token, $expires_at);
            
            // Execute statement
            if (mysqli_stmt_execute($insert_stmt)) {
                mysqli_stmt_close($insert_stmt);

                // Send reset email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    //Server settings
                    $mail->isSMTP();                                           // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                  // Enable SMTP authentication
                    $mail->Username   = 'librarymanagementsystem24@gmail.com';                // SMTP username (your Gmail address)
                    $mail->Password   = 'ekya ziwu ghaf ndab';                 // SMTP password (your Gmail password or app password)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;        // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                    $mail->Port       = 587;                                   // TCP port to connect to

                    //Recipients
                    $mail->setFrom('no-reply@yourdomain.com', 'Your Name');
                    $mail->addAddress($email);                                // Add a recipient

                    // Content
                    $mail->isHTML(true);                                       // Set email format to HTML
                    $mail->Subject = 'PASSWORD RESET REQUEST';
                    $mail->Body    = 'Click the link to reset your password: <a href="http://localhost/reset_password.php?token=' . $token . '">Reset Password</a>';
                    $mail->AltBody = 'Click the link to reset your password: http://localhost/reset_password.php?token=' . $token;

                    $mail->send();

                    // Popup message using JavaScript
                    echo '<script>alert("Password reset email sent."); window.location.href = "index.php";</script>';
                    exit();
                } catch (Exception $e) {
                    echo '<script>alert("Failed to send reset email. Error: ' . $mail->ErrorInfo . '");</script>';
                }
            } else {
                echo '<script>alert("Error executing insert statement: ' . mysqli_stmt_error($insert_stmt) . '");</script>';
            }
        } else {
            echo '<script>alert("Error preparing insert statement: ' . mysqli_error($connection) . '");</script>';
        }
    } else {
        echo '<script>alert("Invalid email address.");</script>';
    }
} else {
    echo '<script>alert("Invalid request.");</script>';
}

// Close the database connection
mysqli_close($connection);
?>
