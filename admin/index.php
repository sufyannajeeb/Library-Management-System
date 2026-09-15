<?php
ob_start();
session_start();

if (isset($_SESSION['email'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Library Management System</title>
    <link rel="stylesheet" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('../images/adm.jpg'); /* Correct path and file name */
            background-size: cover;
            background-position: center center; /* Center the background image */
            background-repeat: no-repeat;
            background-attachment: fixed; /* Make the background stay in place when the page is scrolled */
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0); /* Transparent background */
        }

        .navbar .navbar-brand, .navbar .nav-link {
            color: white;
        }

        #main_content {
            background-color: transparent; /* Slightly transparent white */
            padding: 50px;
            color: #fff;
            min-height: 450px;
            position: absolute;
            top: 50%;
            left: 75%;
            transform: translate(-50%, -50%); /* Center the content box */
        }

        .btn-primary {
            background-color: #000;
            color:#fff;
            border-color: #fff;
        }

        .btn-primary:hover {
            background-color: #fff;
            color:#000;
        }

        .alert-danger {
            padding: 10px;
            margin-top: 10px;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Library Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">USER LOGIN</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4" id="main_content">
            <h3 class="text-center mb-4">Admin Login </h3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="email">Email ID:</label>
                    <input type="text" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
            </form>
            <?php
            if (isset($_POST['login'])) {
                $connection = mysqli_connect("localhost", "root", "", "lms");

                if (!$connection) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $email = mysqli_real_escape_string($connection, $_POST['email']);
                $password = mysqli_real_escape_string($connection, $_POST['password']);

                $query = "SELECT * FROM admins WHERE email = '$email'";
                $query_run = mysqli_query($connection, $query);

                if ($query_run) {
                    $row = mysqli_fetch_assoc($query_run);
                    if ($row) {
                        if (password_verify($password, $row['password'])) {
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['email'] = $row['email'];
                            header("Location: admin_dashboard.php");
                            exit();
                        } else {
                            echo "<div class='alert alert-danger text-center'>Wrong Password !!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger text-center'>Email not found !!</div>";
                    }
                } else {
                    echo "Error: " . mysqli_error($connection);
                }

                mysqli_close($connection);
            }
            ?>
        </div>
    </div>
</div>

<script src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
<script src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
</body>
</html>