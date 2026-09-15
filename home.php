<?php
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-BOOK (User)</title>
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url(images/indx.jpeg.jpg);
            display: flex;
            flex-direction: column;
        }

        .nav-links {
            font-weight: bold;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-links a {
            color: #6c757d;
            font-weight: bold;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1.2em;
            transition: color 0.3s, transform 0.2s;
        }

        .nav-links a:hover {
            color: #255876;
            transform: scale(1.1);
        }

        .hero-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 75px;
            color: #333;
            text-align: left;
            background: transparent;
            color: #fff;
            margin: 0px;
            animation: slideInFromLeft 2s;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #fff;
            animation: fadeInText 2s ease-out;
        }

        .hero-section p {
            font-size: 1.2rem;
            color: #255876;
            animation: fadeInText 2s ease-out;
        }

        @keyframes fadeInText {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .login-form {
            width: 400px;
            background: transparent;
            color: #fff;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: right;
            padding: 30px;
            margin: 50px 20px;
            animation: slideInFromRight 2s;
        }

        @keyframes slideInFromRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            color: #ff6f61;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-password:hover {
            color: #e85d75;
        }

        .content {
            flex: 1;
        }

        footer {
            background: linear-gradient(to bottom, grey, black);
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
            color: white;
            text-align: center;
            padding: 10px 0;
            position: sticky;
            bottom: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            animation: fadeIn 2s;
        }

        footer a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
            margin: 0 10px;
        }

        footer a:hover {
            color: #255876;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light nav-links">
        <a class="navbar-brand" href="#">LIBRARY MANAGEMENT SYSTEM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="admin/index.php">ADMIN</a></li>
                <li class="nav-item"><a class="nav-link" href="signup.php">REGISTER</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php">BLOG</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">CONTACT</a></li>
                <li class="nav-item"><a class="nav-link btn btn-light" href="signup.php">SIGN UP</a></li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid content">
        <div class="row">
            <div class="col-md-6 hero-section">
                <div>
                    <h1>Welcome to Our Library</h1>
                    <p>Login to manage your account and explore our book collection.</p>
                </div>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center">
                <div class="login-form">
                    <h3 class="text-center">LOGIN FORM</h3>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="email">Email ID:</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                        <div class="text-center mt-3">
                            <a href="signup.php">Not registered yet?</a>
                        </div>
                    </form>
                    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
                    <?php 
                    if (isset($_POST['login'])) {
                        $connection = mysqli_connect("localhost", "root", "", "lms");
                        if (!$connection) {
                            die("Database connection failed: " . mysqli_connect_error());
                        }
                        $email = mysqli_real_escape_string($connection, $_POST['email']);
                        $password = mysqli_real_escape_string($connection, $_POST['password']);
                        
                        $query = "SELECT * FROM users WHERE email = '$email'";
                        $query_run = mysqli_query($connection, $query);
                        if ($query_run) {
                            if (mysqli_num_rows($query_run) > 0) {
                                $row = mysqli_fetch_assoc($query_run);
                                if (password_verify($password, $row['password'])) {
                                    $_SESSION['name'] = $row['name'];
                                    $_SESSION['email'] = $row['email'];
                                    $_SESSION['id'] = $row['id'];
                                    header('Location: user_dashboard.php');
                                    exit();
                                } else {
                                    echo '<div class="alert alert-danger">Invalid email or password.</div>';
                                }
                            } else {
                                echo '<div class="alert alert-danger">No user found with this email.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger">Query failed: ' . mysqli_error($connection) . '</div>';
                        }
                        mysqli_close($connection);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 E-BOOK Library. All rights reserved. <a href="privacy_policy.php">Privacy Policy</a> | <a href="terms_of_service.php">Terms of Service</a></p>
    </footer>
</body>
</html>
