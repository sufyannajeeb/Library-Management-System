<?php
session_start();

// Check if the admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Query to fetch issued books details
$query = "SELECT 
            issued_books.book_name, 
            issued_books.book_author, 
            issued_books.issue_date,
            users.name AS user_name
          FROM issued_books 
          LEFT JOIN users ON issued_books.student_id = users.id 
          WHERE issued_books.status = 'Approved'";  // Adjust 'Approved' if your status is different
$query_run = mysqli_query($connection, $query);
if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Issued Books</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        .navbar-custom {
            background-color: #343a40;
        }
        .navbar-custom .navbar-brand, .navbar-custom .nav-link, .navbar-custom .navbar-text {
            color: #ffffff;
        }
        .marquee-container {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
            background-color: #343a40;
            color: white;
            padding: 10px;
        }
        .container {
            margin-top: 20px;
        }
        h4, th {
            text-align: center;
            color: #343a40;
        }
        .table-custom {
            background-color: #ffffff;
            width: 100%;
            text-align: center;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #dee2e6;
            padding: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
        <div class="navbar-text ml-auto">
            <strong>Welcome: <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></strong>
        </div>
        <div class="navbar-text ml-3">
            <strong>Email: <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></strong>
        </div>
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="view_profile.php">View Profile</a>
                    <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                    <a class="dropdown-item" href="change_password.php">Change Password</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="marquee-container">
    <marquee>This is library management system. Library opens at 8:00 AM and closes at 4:00 PM</marquee>
</div>

<div class="container">
    <h4>Issued Books Details</h4><br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Book Name</th>
                        <th>Author</th>
                        <th>Issued At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch and display the results
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        $user_name = htmlspecialchars($row['user_name'] ?? '');
                        $book_name = htmlspecialchars($row['book_name'] ?? '');
                        $book_author = htmlspecialchars($row['book_author'] ?? '');
                        $issue_date = htmlspecialchars($row['issue_date'] ?? '');
                        echo "<tr>
                                <td>$user_name</td>
                                <td>$book_name</td>
                                <td>$book_author</td>
                                <td>$issue_date</td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($connection);
?>
