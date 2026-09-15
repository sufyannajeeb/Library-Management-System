<?php
ob_start();
session_start();

// Check if the user is logged in, else redirect to the login page
if (!isset($_SESSION['email'])) {
    echo '<script type="text/javascript">
            alert("Session expired. Please log in again.");
            window.location.href = "index.php";
          </script>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #e0e0e0; /* Light grey background */
            color: #333; /* Dark text for readability */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-custom {
            background-color: #343a40; /* Dark navbar */
            padding: 1rem;
        }
        .navbar-custom .navbar-brand, 
        .navbar-custom .navbar-nav .nav-link {
            color: #f8f9fa; /* Light text */
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #00aaff; /* Highlight color on hover */
        }
        .form-container {
            background-color: #ffffff; /* White background for the form */
            padding: 5rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow for depth */
            width: 100%;
            max-width: 500px; /* Adjusted card size */
            margin: 2rem auto; /* Center the form */
        }
        .form-container h4 {
            margin-bottom: 1.5rem;
            color: #333;
            text-align: center;
            font-weight: 600;
        }
        .form-control {
            background-color: #f8f9fa; /* Light background for inputs */
            color: #495057; /* Dark text for inputs */
            border: 1px solid #ced4da; /* Light border for inputs */
            border-radius: 5px;
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        .form-control:focus {
            border-color: #00aaff;
            box-shadow: 0 0 0 0.2rem rgba(0, 170, 255, 0.25);
        }
        .btn-primary {
            background-color: #6c757d; /* Grey button for contrast */
            border: none;
            border-radius: 5px;
            padding: 0.75rem;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #5a6268; /* Darker grey on hover */
        }
        .footer {
            background-color: transparent; /* Light background for footer */
            color: #333; /* Black text */
            text-align: center;
            padding: 1rem;
            margin-top: auto; /* Push footer to the bottom */
        }
        .footer a {
            color: #00aaff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="user_dashboard.php">USER DASHBOARD (LMS)</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="view_profile.php"><i class="fas fa-user"></i> My Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="form-container">
    <h4>Change Password</h4>
    <form action="update_password.php" method="post">
        <div class="mb-3">
            <label for="current_password" class="form-label">Enter Current Password:</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Enter New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Password</button>
    </form>
</div>

<footer class="footer">
    <p>&copy; <?php echo date('Y'); ?> Your Company. <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>