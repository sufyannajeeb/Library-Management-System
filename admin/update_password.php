<?php
ob_start();
// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all users
$query = "SELECT id, password FROM admins";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $plain_password = $row['password'];

        // Hash the plain password
        $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_query = "UPDATE admins SET password = ? WHERE id = ?";
        $stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($stmt, "si", $hashed_password, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "Password for user ID $id updated successfully.<br>";
        } else {
            echo "Error updating password for user ID $id: " . mysqli_error($connection) . "<br>";
        }
    }
} else {
    echo "No users found.";
}

// Close the connection
mysqli_close($connection);
?>
