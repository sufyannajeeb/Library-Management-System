<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($connection, $_GET['id']);

    // Delete user from the database
    $delete_query = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($connection, $delete_query)) {
        // Redirect to the users page with a success message
        $_SESSION['message'] = "User removed successfully.";
        header('Location: users_list.php'); // Redirect to the users list page
        exit;
    } else {
        // Redirect to the users page with an error message
        $_SESSION['message'] = "Error removing user.";
        header('Location: users_list.php');
        exit;
    }
} else {
    // Redirect to the users page if 'id' is not set
    header('Location: users_list.php');
    exit;
}

// Close the database connection
mysqli_close($connection);
?>
