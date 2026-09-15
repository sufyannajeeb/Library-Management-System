<?php
require("admin_functions.php"); // Ensure this path is correct
ob_start();
session_start();

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

// Include the database connection file
include('../admin/db_connect.php');

// Fetch borrowing history with student names and book names
$query = "SELECT bra.*, u.name AS student_name, b.book_name 
          FROM book_requests_archive bra
          JOIN users u ON bra.user_id = u.id
          JOIN books b ON bra.book_id = b.book_id
          ORDER BY bra.request_date DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Borrowing History</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: lightgrey;
            background-size: cover;
            background-attachment: fixed;
            color: #000;
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
            background-color: #333;
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
        .main-content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .main-content.active {
            margin-left: 250px;
        }
        /* Table Styles */
        .table th, .table td {
            text-align: center;
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
                <a class="nav-link home-btn" href="history.php" style="color: #000;">History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link home-btn" href="admin_dashboard.php" style="color: #000;">Dashboard</a>
            </li>
            <li class="nav-item">
                <button class="logout-btn" onclick="window.location.href='history.php?logout=true'">Logout</button>
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
        <h1>Book Borrowing History</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>User ID</th>
                    <th>Student Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Approved At</th>
                    <th>Return Date</th>
                    <th>Book Collected</th>
                    <th>Approval Date</th>
                    <th>Created At</th>
                    <th>Return Status</th>
                    <th>Due Date</th>
                    <th>Email Sent</th>
                    <th>Extend Requested</th>
                    <th>New Return Date</th>
                    <th>Extension Reason</th>
                </tr>
            </thead>
            <tbody>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['request_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['book_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['book_name'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['user_id'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['student_name'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['request_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['approved_at'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['return_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['book_collected'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['approval_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['created_at'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['return_status'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['due_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['email_sent'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['extend_requested'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['new_return_date'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($row['extension_reason'] ?? ''); ?></td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="17">No history available.</td>
        </tr>
    <?php endif; ?>
</tbody>

        </table>
    </div>
</div>

<script src="../jquery-3.4.1.min.js"></script>
<script src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
<script>
    // Toggle sidebar visibility
    document.getElementById("menu-btn").addEventListener("click", function() {
        var sidebar = document.getElementById("sidebar");
        var mainContent = document.getElementById("main-content");
        var navbarNav = document.getElementById("navbarNav");

        if (sidebar.style.left === "-250px") {
            sidebar.style.left = "0";
            mainContent.classList.add("active");
            navbarNav.classList.add("show");        } else {
            sidebar.style.left = "-250px";
            mainContent.classList.remove("active");
            navbarNav.classList.remove("show");
        }
    });
</script>
</body>
</html>
