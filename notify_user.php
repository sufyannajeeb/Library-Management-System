<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Include the necessary files from the admin directory
include './admin/db_connect.php'; 
include './admin/send_availability_email.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['id'];

    // Delete existing notification request if it exists
    $delete_query = "DELETE FROM notifications WHERE user_id = ? AND book_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $user_id, $book_id);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Insert a new notification request
    $query = "INSERT INTO notifications (user_id, book_id, notification_type, notified, created_at) VALUES (?, ?, 'Availability', 0, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $book_id);

    if ($stmt->execute()) {
        // Fetch user details
        $user_query = "SELECT email, name FROM users WHERE id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();
        $user_row = $user_stmt->get_result()->fetch_assoc();
        $email = $user_row['email'];
        $user_name = $user_row['name'];

        // Fetch book details
        $book_query = "SELECT book_name FROM books WHERE book_id = ?";
        $book_stmt = $conn->prepare($book_query);
        $book_stmt->bind_param("i", $book_id);
        $book_stmt->execute();
        $book_row = $book_stmt->get_result()->fetch_assoc();
        $book_name = $book_row['book_name'];

        // Send confirmation email
        $subject = "Book Availability Notification Request";
        try {
            sendNotificationConfirmationEmail($email, $subject, $book_name, $user_name);
        } catch (Exception $e) {
            $response = ['message' => "Failed to send notification email."];
            error_log("Error sending email: " . $e->getMessage());
            echo json_encode($response);
            exit();
        }

        // Success response
        $response = ['message' => "You will be notified when this book becomes available."];
        echo json_encode($response);
    } else {
        // Failure response
        $response = ['message' => "Failed to request notification."];
        echo json_encode($response);
    }

    // Close statements and connection
    $stmt->close();
    $conn->close();
    exit();
}
?>
