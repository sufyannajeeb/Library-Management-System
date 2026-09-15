<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: error.html");
    exit();
}

// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    header("Location: error.html");
    exit();
}

// Get the POST data
$request_id = $_POST['request_id'] ?? '';
$new_return_date = $_POST['new_return_date'] ?? '';
$extension_reason = $_POST['extension_reason'] ?? '';

// Validate data
if (empty($request_id) || empty($new_return_date) || empty($extension_reason)) {
    header("Location: error.html");
    exit();
}

// Update the book request in the database
$sql = "UPDATE book_requests SET extend_requested = 1, new_return_date = ?, extension_reason = ? WHERE request_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('ssi', $new_return_date, $extension_reason, $request_id);

if ($stmt->execute()) {
    // Redirect to success page
    header("Location: success.php");
} else {
    // Redirect to error page
    header("Location: error.php");
}

$stmt->close();
$connection->close();
?>
