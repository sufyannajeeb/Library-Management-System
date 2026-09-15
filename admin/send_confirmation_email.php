<?php
function sendConfirmationEmail($to, $subject, $book_name, $user_name) {
    $message = "Hello $user_name,\n\n";
    $message .= "Thank you for your notification request.\n";
    $message .= "We have received your request for the book '$book_name'.\n";
    $message .= "You will be notified when this book becomes available.\n\n";
    $message .= "Best regards,\nLibrary Team";

    // Send the email
    if (mail($to, $subject, $message)) {
        return true;
    } else {
        return false;
    }
}
?>
