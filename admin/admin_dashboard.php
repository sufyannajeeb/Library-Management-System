<?php
require("admin_functions.php"); // Ensure this path is correct
ob_start();
session_start();

// Set session timeout duration (e.g., 30 minutes)
$session_timeout = 1800; // 1800 seconds = 30 minutes

// Check for session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_timeout)) {
    // Last request was more than 30 minutes ago
    session_unset();     // Unset $_SESSION variables
    session_destroy();   // Destroy session data
    header("Location: ../index.php");
    exit();
}

// Update last activity time stamp
$_SESSION['LAST_ACTIVITY'] = time();

// Check if admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// Fetch admin name from session or database
$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

// Fetch counts with default values
$user_count = get_user_count() ?? 0;
$book_count = get_book_count() ?? 0;
$category_count = get_category_count() ?? 0;
$author_count = get_author_count() ?? 0;
$issued_books_count = get_issue_book_count() ?? 0;
$pending_requests_count = get_pending_request_count() ?? 0;

// Add cache control headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-BOOK (admin)</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <style>
    body {
    font-family: 'Poppins', sans-serif;
    background-image: url('../images/img45.jpg'); /* Ensure the path is correct */
    background-size: cover;
    background-attachment: fixed;
    color: #fff;
    margin: 0;
    padding: 0;
}

/* Navbar Styles */
.navbar-custom {
    background-color: transparent;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    padding: 0 15px;
}

.navbar-custom .navbar-brand {
    color: #fff;
}

.navbar-custom .navbar-nav {
    margin-left: auto;
}

.navbar-custom .home-btn {
    background-color: grey;
    color: #000;
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    font-size: 16px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    margin-right: 10px;
}

.navbar-custom .home-btn:hover {
    background-color: #fff;
    color: #000;
}

.navbar-custom .logout-btn {
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 20px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.navbar-custom .logout-btn:hover {
    background-color: red; /* Red background on hover */
    color: #fff;
}

.navbar-custom .menu-btn {
    background-color: transparent;
    color: #000;
    border: none;
    padding: 10px 15px;
    font-size: 24px;
    border-radius: 5px;
    cursor: pointer;
    outline: none;
    margin-right: auto;
    transition: color 0.3s ease;
}

.navbar-custom .menu-btn:hover {
    color: grey;
}

/* Sidebar Styles */
.sidebar {
    height: 100%;
    width: 250px;
    position: fixed;
    top: 50px; /* Adjust the top position to push the sidebar lower */
    left: -250px; /* Hidden by default */
    background-color: rgba(52, 58, 64, 0.9);
    padding-top: 20px;
    color: #ffc107;
    z-index: 1;
    transition: 0.3s ease; /* Smooth transition for opening/closing */
}

.sidebar a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: #fff;
    display: block;
}

.sidebar a:hover {
    background-color: #000;
    color: #fff;
}

/* Main Content Styles */
.main-content {
    margin-left: 0;
    padding: 20px;
    transition: margin-left 0.3s ease;
}

.main-content.active {
    margin-left: 250px;
}

/* Button Styles */
.btn {
    border: 2px solid #fff;
    background-color: transparent;
    color: #fff;
    border-radius: 20px;
    padding: 8px 20px;
    transition: all 0.3s ease;
}

.btn:hover {
    background-color: #fff;
    color: #343a40;
}

/* Card Styles */
.card {
    background-color: rgba(0, 0, 0, 0.7);
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    margin-bottom: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.card-header, .card-text {
    color: #fff;
}

/* Dropdown Styles */
.dropdown-menu {
    background-color: #343a40;
    border: none;
}

.dropdown-item {
    display: block;
    padding: 0;
}

.dropdown-button {
    background-color: #000;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    text-align: center;
    width: 100%;
    font-size: 16px;
}

.dropdown-button:hover {
    background-color: #333;
}

/* Container Styles */
.container-fluid {
    max-width: 1200px;
    padding: 0 15px;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .sidebar {
        width: 180px;
    }

    .sidebar a {
        font-size: 16px;
    }

    .main-content.active {
        margin-left: 200px;
    }

    .navbar-custom .menu-btn {
        margin-right: 0;
    }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <button class="menu-btn" type="button" id="menu-btn">
        ☰
    </button>
    
    <div class="navbar-collapse collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link home-btn history-btn" href="history.php"style="color: #000;">
                    <i class="fas fa-history"></i> History
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link home-btn home-btn" href="admin_dashboard.php" style="color: #000;">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <button class="logout-btn" onclick="window.location.href='admin_dashboard.php?logout=true'">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </li>
        </ul>
    </div>
</nav>




<div id="sidebar" class="sidebar">
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="add_book.php">Add New Book</a>
    <a href="manage_book.php">Manage Books</a>
    <a href="add_cat.php">Add New Category</a>
    <a href="manage_cat.php">Manage Category</a>
    <a href="add_author.php">Add New Author</a>
    <a href="manage_author.php">Manage Author</a>
</div>

<div id="main-content" class="main-content">
    <div class="container-fluid">
        <div class="row">
            <!-- Dashboard Cards (Registered User, Total Book, etc.) -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Registered User</div>
                    <div class="card-body">
                        <p class="card-text">No. total Users: <?php echo htmlspecialchars($user_count); ?></p>
                        <a class="btn btn-danger" href="Regusers.php">View Registered Users</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Total Book</div>
                    <div class="card-body">
                        <p class="card-text">No of books available: <?php echo htmlspecialchars($book_count); ?></p>
                        <a class="btn btn-success" href="Regbooks.php">View All Books</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Book Categories</div>
                    <div class="card-body">
                        <p class="card-text">No of Book's Categories: <?php echo htmlspecialchars($category_count); ?></p>
                        <a class="btn btn-warning" href="Regcat.php">View Categories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">No. of Authors</div>
                    <div class="card-body">
                        <p class="card-text">No of Authors: <?php echo htmlspecialchars($author_count                        ); ?></p>
                        <a class="btn btn-info" href="Regauthor.php">View Authors</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Pending Requests</div>
                    <div class="card-body">
                        <p class="card-text">No of Pending Requests: <?php echo htmlspecialchars($pending_requests_count); ?></p>
                        <a class="btn btn-secondary" href="view_book_requests.php">View Pending Requests</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../jquery-3.4.1.min.js"></script>
<script src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
<script>
    // Toggle sidebar visibility
    // Toggle sidebar visibility
document.getElementById("menu-btn").addEventListener("click", function() {
    var sidebar = document.getElementById("sidebar");
    var mainContent = document.getElementById("main-content");
    var navbarNav = document.getElementById("navbarNav");

    if (sidebar.style.left === "-250px") {
        sidebar.style.left = "0";
        mainContent.classList.add("active");
        navbarNav.classList.add("show"); // Ensure navbar collapses correctly
    } else {
        sidebar.style.left = "-250px";
        mainContent.classList.remove("active");
        navbarNav.classList.remove("show"); // Ensure navbar collapses correctly
    }
});

</script>
</body>
</html>