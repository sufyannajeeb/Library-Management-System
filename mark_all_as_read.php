<?php
session_start();
require '../admin/db_connect.php'; // Adjusting path according to your structure

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    
    // Prepare the statement to update notifications
    $stmt = $connection->prepare("UPDATE user_notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");

    // Check for statement preparation error
    if (!$stmt) {
        error_log('Statement preparation failed: ' . $connection->error);
        echo json_encode(['success' => false, 'message' => 'Statement preparation failed.']);
        exit();
    }
    
    // Bind parameters
    $stmt->bind_param("i", $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read.']);
    } else {
        error_log('Failed to update notifications: ' . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Failed to update notifications.']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
}

// Close the database connection
$connection->close();
?>
