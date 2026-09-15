<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Query to fetch available books
$sql = "SELECT books.book_id, books.book_name, authors.author_name, category.cat_name, books.book_price 
        FROM books 
        JOIN authors ON books.author_id = authors.author_id 
        JOIN category ON books.cat_id = category.cat_id 
        WHERE books.is_issued = 0";
$query_run = $connection->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Available Books</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 60px; /* Adjust based on the height of your navbar */
        }
        .navbar-fixed {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }
        .fixed-buttons {
            position: fixed;
            top: 56px; /* Adjust based on the height of your navbar */
            left: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            padding: 10px 0;
            z-index: 1000;
            display: flex;
            justify-content: center;
        }
        .table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .table thead {
            background-color: #6A0DAD;
            color: white;
        }
        .table thead th {
            border: none;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-fixed" style="background-color: #6A0DAD">
    <div class="container-fluid">
        <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="fixed-buttons">
    <button type="button" class="btn btn-primary" onclick="document.getElementById('books_form').submit();">Issue Selected Books</button>
</div>

<div class="container" style="margin-top: 20px;">
    <h2 class="text-center">Available Books</h2>
    <form id="books_form" action="issue_books.php" method="POST">
        <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($query_run->num_rows > 0) {
                    while ($row = $query_run->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='book_ids[]' value='" . htmlspecialchars($row['book_id']) . "'></td>";
                        echo "<td>" . htmlspecialchars($row['book_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['book_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['author_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['cat_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['book_price']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No books available currently.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </form>
</div>

<script src="bootstrap-4.4.1/js/jquery_latest.js"></script>
<script src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$connection->close();
?>
