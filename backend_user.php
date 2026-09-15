<?php
session_start();
include 'admin/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);



file_put_contents('debug.log', "Backend reached\n", FILE_APPEND);




if (isset($data['message'])) {
    $userMessage = trim($data['message']); // Trim to remove unnecessary spaces

    if (empty($userMessage)) {
        echo "Message cannot be empty.";
        exit;
    }

    // Check if there's an active conversation for the user
    $query = "SELECT id FROM conversations WHERE user_id = ? AND status = 'active'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Create a new conversation if none exists
        $query = "INSERT INTO conversations (user_id, admin_id, status) VALUES (?, ?, 'active')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $admin_id);
        $stmt->execute();
        $conversation_id = $stmt->insert_id;
    } else {
        // Get existing conversation ID
        $row = $result->fetch_assoc();
        $conversation_id = $row['id'];
    }

    // Insert the message into the messages table
    $query = "INSERT INTO messages (conversation_id, sender, receiver, message, timestamp, status) 
              VALUES (?, 'user', 'admin', ?, NOW(), 'unread')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $conversation_id, $userMessage);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Message sent successfully.";
    } else {
        echo "Failed to send message.";
    }
} else {
    echo "No message received.";
}
?>


backend_user.php