<?php
ob_start();
session_start();

// Ensure the user is authenticated
if (!isset($_SESSION['email'])) {
    ?>
    <script type="text/javascript">
        alert("Session expired. Please log in again.");
        window.location.href = "index.php";
    </script>
    <?php
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form data is set
if (isset($_POST['name'], $_POST['email'], $_POST['mobile'], $_POST['address'])) {
    // Sanitize and validate inputs
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $session_email = $_SESSION['email'];

    // Update user profile in the database
    $query = "UPDATE users SET name = ?, email = ?, mobile = ?, address = ? WHERE email = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $mobile, $address, $session_email);

    if (mysqli_stmt_execute($stmt)) {
        ?>
        <script type="text/javascript">
            alert("Updated successfully...");
            window.location.href = "user_dashboard.php";
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert("Error updating profile: <?php echo mysqli_error($connection); ?>");
            window.location.href = "edit_profile.php";
        </script>
        <?php
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    ?>
    <script type="text/javascript">
        alert("Form submission error: Missing required fields.");
        window.location.href = "edit_profile.php";
    </script>
    <?php
}
?>
