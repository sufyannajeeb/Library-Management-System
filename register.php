<?php
ob_start();
$connection = mysqli_connect("localhost","root","");
$db = mysqli_select_db($connection,"lms");

// Check for errors in the connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert query
    $query = "INSERT INTO users (name, email, password, mobile, address) VALUES ('$name', '$email', '$hashedPassword', '$mobile', '$address')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        ?>
        <script type="text/javascript">
            alert("Registration successful...You may Login now !!");
            window.location.href = "index.php";
        </script>
        <?php
    } else {
        ?>
        <script type="text/javascript">
            alert("Registration failed. Please try again.");
            window.location.href = "register.php";
        </script>
        <?php
    }
}

mysqli_close($connection);
?>
