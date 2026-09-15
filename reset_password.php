<?php
ob_start();
session_start();
# Fetch data from database
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");
$name = "";
$email = "";
$mobile = "";
$address = "";
$query = "SELECT * FROM users WHERE email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1.5rem;
            text-transform: uppercase;
        }
        .navbar-brand:hover {
            color: #adb5bd;
        }
        .navbar .nav-link {
            color: #fff;
            margin-right: 15px;
        }
        .navbar .nav-link:hover {
            color: #adb5bd;
        }
        .marquee-container {
            background-color: #495057;
            padding: 10px;
            color: #fff;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
        }
        h4 {
            color: #343a40;
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
            text-transform: uppercase;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #ced4da;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(52, 58, 64, 0.3);
            transition: background-color 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        .container-fluid, .dropdown-menu {
            padding-left: 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="user_dashboard.php">User Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-user"></i> Welcome: <?php echo $_SESSION['name']; ?></span>
                </li>
                <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-envelope"></i> <?php echo $_SESSION['email']; ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-cog"></i> My Profile
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="marquee-container">
    <span>Library Management System: Library opens at 8:00 AM and closes at 4:00 PM</span>
</div>

<div class="container mt-5">
    <center><h4>Edit Profile</h4><br></center>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form action="update.php" method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" class="form-control" value="<?php echo $mobile; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea rows="3" cols="40" name="address" class="form-control"><?php echo $address; ?></textarea>
                </div>
                <button type="submit" name="update" class="btn btn-primary btn-block">Update</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>