<?php
require("functions.php");
ob_start();
session_start();

// Check if admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle search query
$search = isset($_POST['search']) ? mysqli_real_escape_string($connection, $_POST['search']) : '';

// Query to fetch authors and their books based on search
$query = "SELECT a.author_name, b.book_name 
          FROM authors a
          LEFT JOIN books b ON a.author_id = b.author_id 
          WHERE a.author_name LIKE '%$search%' OR b.book_name LIKE '%$search%'";
$query_run = mysqli_query($connection, $query);
if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}

// Count the number of authors found
$author_count = mysqli_num_rows($query_run);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registered Authors</title>
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
        .search-bar {
            margin: 20px 0;
            text-align: center;
        }
        .container {
            margin-top: 20px;
        }
        .h4, .p {
            text-align: center;
            color: #343a40;
        }
        .table-custom {
            background-color: #ffffff;
        }
        .table-custom th, .table-custom td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
        }
        #scrollToTopButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            display: block; /* Ensure the button is always visible */
            transition: background-color 0.3s;
        }
        #scrollToTopButton:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
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
                    <a class="nav-link" href="index.php">Logout</a>
                </li>
            </ul>
        </div>
        <span class="navbar-text ml-auto">
            <strong>Welcome: (admin) <?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?></strong>
        </span>
        <span class="navbar-text">
            <strong>Email: <?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></strong>
        </span>
    </div>
</nav>

<div class="search-bar">
    <form method="POST" action="">
        <input type="text" name="search" class="form-control" placeholder="Search for authors or books..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-primary mt-2">Search</button>
    </form>
</div>

<div class="container">
    <h4 class="h4">Registered Authors</h4>
    <p class="p">Total Authors Found: <?php echo $author_count; ?></p>
    <br>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Author Name</th>
                        <th>Book Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $number = 1;
                    while ($row = mysqli_fetch_assoc($query_run)) {
                        echo "<tr>
                            <td>" . $number++ . "</td>
                            <td>" . htmlspecialchars($row['author_name'] ?? 'N/A') . "</td>
                            <td>" . htmlspecialchars($row['book_name'] ?? 'N/A') . "</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

<button id="scrollToTopButton" onclick="scrollToTop()">Move to Top</button>

<script>
    function scrollToTop() {
        const button = document.getElementById('scrollToTopButton');
        if (button.innerText === 'Move to Top') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            button.innerText = 'Move to Bottom';
        } else {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
            button.innerText = 'Move to Top';
        }
    }

    // Always show the button
    window.onscroll = function() {
        const button = document.getElementById('scrollToTopButton');
        button.style.display = 'block'; // Ensure the button is always visible
    };
</script>
</body>
</html>

<?php
mysqli_close($connection);
?>
