<?php
ob_start();
session_start();
#fetch data from database
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, "lms");
$name = "";
$email = "";
$mobile = "";
$query = "select * from users where email = '$_SESSION[email]'";
$query_run = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($query_run)) {
    $name = $row['name'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $address = $row['address'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <meta charset="utf-8" name="viewport" content="width=device-width,intial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>

    <style type="text/css">
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        #main_content {
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .navbar {
            background-color: #343a40;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: #fff !important;
            font-weight: bold;
        }

        .nav-link {
            color: #ddd !important;
        }

        .marquee-container {
            width: 100%;
            background-color: #343a40;
            color: #fff;
            padding: 10px 0;
            margin-top: 20px;
            text-align: center;
            font-weight: 500;
        }

        .form-control {
            background-color: #f9f9f9;
            color: #333;
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        h4 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="user_dashboard.php">USER DASHBOARD (LMS)</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="marquee-container">
        <span>
            <marquee>This is library management system. Library opens at 8:00 AM and closes at 4:00 PM</marquee>
        </span>
    </div>

    <div id="main_content">
        <h4>Student Profile Detail</h4>
        <form>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" value="<?php echo $name; ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" value="<?php echo $email; ?>" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile:</label>
                <input type="text" value="<?php echo $mobile; ?>" class="form-control" disabled>
            </div>
            <div class="form-group">
                <label for="email">Address:</label>
                <input type="text" value="<?php echo $address; ?>" class="form-control" disabled>
            </div>
        </form>
    </div>
</body>

</html>