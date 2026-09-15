<?php
session_start();
$connection = new mysqli("localhost", "root", "", "lms");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Assuming that the logged-in user's ID is stored in $_SESSION['id']
$logged_in_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

if ($logged_in_user_id && isset($_POST['remind_users'])) {
    // Fetch the name and overdue book requests for the logged-in user
    $sql = "
        SELECT u.id, u.name, u.email 
        FROM users u 
        INNER JOIN book_requests br ON u.id = br.user_id 
        INNER JOIN books b ON br.book_id = b.book_id 
        WHERE br.is_active = 1 
          AND br.status = 'Approved' 
          AND b.is_issued = 1 
          AND u.id = ? 
          AND br.payment_verified = 0  -- Check if payment is not verified
          AND (
            (br.new_return_date IS NOT NULL AND br.new_return_date < NOW()) OR
            (br.new_return_date IS NULL AND br.return_date < NOW())
          )
    ";

    // Prepare the statement and bind the logged-in user's ID
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $logged_in_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user's details to set the session name
    $user = $result->fetch_assoc();
    
    // Store the user's name in the session
    $_SESSION['user_name'] = $user['name'];

    // Check if the logged-in user has overdue books with unverified payment
    if ($result->num_rows > 0) {
        // Send email only to the logged-in user found in the result
        // Example: send_email($user['email'], $user['name']);

        // Set session message with the user's name
        $_SESSION['warning'] = "Greetings, " . $_SESSION['user_name'] . "! Reminder sent to you for overdue book requests.";
        
        // Insert a notification into the user_notifications table
        $notification_message = "Reminder: You have overdue book requests.";
        $notification_sql = "INSERT INTO user_notifications (user_id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)";
        
        // Prepare and execute the notification statement
        $notification_stmt = $connection->prepare($notification_sql);
        $notification_stmt->bind_param('is', $logged_in_user_id, $notification_message);
        $notification_stmt->execute();
    } else {
        // If no overdue books found for the logged-in user
        $_SESSION['warning'] = "Greetings, " . $_SESSION['user_name'] . ". No overdue books found.";
    }

    // Redirect back to the view_due_books.php
    header("Location: view_due_books.php");
    exit();
}

$connection->close();
?>
