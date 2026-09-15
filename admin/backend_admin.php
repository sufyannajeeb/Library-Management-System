<?php
session_start();
include '../admin/db_connect.php'; // Include the database connection

// Check if a conversation ID is provided
if (isset($_GET['conversation_id'])) {
    $conversation_id = (int) $_GET['conversation_id']; // Sanitize input

    // Fetch messages for the specified conversation
    $query = "
        SELECT 
            m.sender, 
            m.message, 
            m.timestamp 
        FROM messages m
        WHERE m.conversation_id = ?
        ORDER BY m.timestamp ASC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $conversation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display conversation messages
    echo "<h1>Conversation Messages</h1>";
    echo "<table border='1'>";
    echo "<tr><th>Sender</th><th>Message</th><th>Timestamp</th></tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['sender']) . "</td>";
        echo "<td>" . htmlspecialchars($row['message']) . "</td>";
        echo "<td>" . $row['timestamp'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

} else {
    echo "No conversation selected.";
}
?>

