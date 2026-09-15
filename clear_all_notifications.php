<?php
session_start();
require 'admin/db_connect.php'; // Adjust according to your connection script
require 'home.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated.']);
    exit();
}

$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

// Prepare the DELETE statement
$stmt = $connection->prepare("DELETE FROM user_notifications WHERE user_id = ?");
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $connection->error]);
    exit();
}

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'All notifications cleared.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error clearing notifications: ' . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$connection->close();
?>
