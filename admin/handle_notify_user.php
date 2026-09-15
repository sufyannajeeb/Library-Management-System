<?php
include('../admin/db_connect.php'); // Database connection
include('due_alert.php');

$conn = $conn ?? null;

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission for notifying user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null;

    if (!$request_id) {
        die('Invalid request.');
    }

    try {
        // Fetch the user ID associated with the request ID
        $userIdQuery = "SELECT user_id FROM book_requests WHERE request_id = ?";
        $userIdStmt = $conn->prepare($userIdQuery);
        $userIdStmt->bind_param("i", $request_id);
        $userIdStmt->execute();
        $userIdResult = $userIdStmt->get_result();
        $userRow = $userIdResult->fetch_assoc();
        $user_id = $userRow['user_id'];

        // Prepare the warning message
        $message = "Warning: Your book is overdue! A fine of Rs 5 will be deducted for past due date.";

        // Insert the notification into the notifications table
        $insertNotifyStmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $insertNotifyStmt->bind_param("is", $user_id, $message);

        if ($insertNotifyStmt->execute()) {
            // Fetch the user's email for notification
            $userEmailQuery = "SELECT email FROM users WHERE id = ?";
            $userEmailStmt = $conn->prepare($userEmailQuery);
            $userEmailStmt->bind_param("i", $user_id);
            $userEmailStmt->execute();
            $userEmailResult = $userEmailStmt->get_result();
            $userEmailRow = $userEmailResult->fetch_assoc();
            $userEmail = $userEmailRow['email'];

            // Log the email address for debugging
            error_log("Preparing to send email to: " . $userEmail);

            // Send email using the function in due_alert.php
            $subject = "Overdue Book Notification";
            sendDueAlertEmail($userEmail, $subject, $message, 'User');

            // Redirect after sending notification
            header('Location: view_due_books.php?notification_sent=true');
            exit();
        } else {
            throw new Exception("Error inserting notification: " . $insertNotifyStmt->error);
        }

        // Close the statements
        $userIdStmt->close();
        $insertNotifyStmt->close();
        $userEmailStmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        die('An error occurred. Please try again later.');
    }
}
?>
