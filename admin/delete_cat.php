<?php
ob_start();
session_start();

// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if category ID is set
if (isset($_GET['cat_id'])) {
    $cat_id = $_GET['cat_id'];

    // Prepare and execute delete query
    $stmt = $connection->prepare("DELETE FROM category WHERE cat_id = ?");
    $stmt->bind_param("i", $cat_id);

    if ($stmt->execute()) {
        // Redirect to categories page or show success message
        header("Location: view_categories.php");
    } else {
        echo "Error deleting category: " . $connection->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$connection->close();
ob_end_flush();
?>
