<?php
// admin_dashboard.php

session_start();
include '../admin/db_connect.php'; // Include the database connection

// Fetch grouped conversations and message details
$query = "
    SELECT 
        m.conversation_id,
        m.sender AS user_name,
        MAX(m.timestamp) AS last_message_time,
        MAX(m.message) AS last_message,
        COUNT(m.id) AS total_messages
    FROM messages m
    WHERE m.receiver = 'admin' -- Only consider messages sent to the admin
    GROUP BY m.conversation_id, m.sender
    ORDER BY last_message_time DESC";


$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

// Display conversations grouped by user
echo "<h1>Admin Dashboard</h1>";
echo "<table border='1'>";
echo "<tr><th>User</th><th>Last Message</th><th>Last Message Time</th><th>Total Messages</th><th>Actions</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_message']) . "</td>";
    echo "<td>" . $row['last_message_time'] . "</td>";
    echo "<td>" . $row['total_messages'] . "</td>";
    echo "<td><a href='backend_admin.php?conversation_id=" . $row['conversation_id'] . "'>View Messages</a></td>";
    echo "</tr>";
}
echo "</table>";
?>