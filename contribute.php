<?php
// Database connection
$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "lms"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get email from form
$email = $_POST['email'];

// Insert email into contributions table
$sql = "INSERT INTO contributions (email) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    echo "Thank you for your contribution! We will review your submission.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
