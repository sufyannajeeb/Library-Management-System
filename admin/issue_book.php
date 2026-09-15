<?php
ob_start();
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function alertMsg(){
            alert("Book added successfully...");
            window.location.href = "admin_dashboard.php";
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
            </div>
            <font style="color: white"><span><strong>Welcome: <?php echo $_SESSION['name'];?></strong></span></font>
            <font style="color: white"><span><strong>Email: <?php echo $_SESSION['email'];?></strong></span></font>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd">
        <div class="container-fluid">
            <ul class="nav navbar-nav navbar-center">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Books </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_book.php">Add New Book</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_book.php">Manage Books</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Category </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_cat.php">Add New Category</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_cat.php">Manage Category</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">Authors</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="add_author.php">Add New Author</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="manage_author.php">Manage Author</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="issue_book.php">Issue Book</a>
                </li>
            </ul>
        </div>
    </nav><br>
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 4:00 PM</marquee></span><br><br>

    <center><h4>Issue Book</h4><br></center>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Book Name</th>
                        <th>Book Author</th>
                        <th>Student ID</th>
                        <th>Request Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $connection = new mysqli("localhost", "root", "", "lms");

                    // Check connection
                    if ($connection->connect_error) {
                        die("Connection failed: " . $connection->connect_error);
                    }

                    // Query to fetch pending requests with book details
                    $request_query = "
                        SELECT r.request_id, b.book_name, a.author_name AS book_author, r.user_id AS student_id, r.request_date 
                        FROM book_requests r
                        JOIN books b ON r.book_id = b.book_id
                        JOIN authors a ON b.author_id = a.author_id
                        WHERE r.status = 'Pending'
                    ";
                    $request_result = $connection->query($request_query);

                    if ($request_result->num_rows > 0) {
                        while ($row = $request_result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['request_id']}</td>
                                <td>{$row['book_name']}</td>
                                <td>{$row['book_author']}</td>
                                <td>{$row['student_id']}</td>
                                <td>{$row['request_date']}</td>
                                <td>
                                    <form action='' method='post' style='display: inline;'>
                                        <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                        <button type='submit' name='action' value='approve' class='btn btn-success btn-sm'>Approve</button>
                                    </form>
                                    <form action='' method='post' style='display: inline;'>
                                        <input type='hidden' name='request_id' value='{$row['request_id']}'>
                                        <button type='submit' name='action' value='reject' class='btn btn-danger btn-sm'>Reject</button>
                                    </form>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No pending requests.</td></tr>";
                    }

                    $connection->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $connection = new mysqli("localhost", "root", "", "lms");

    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    if (isset($_POST['request_id']) && isset($_POST['action'])) {
        $request_id = $connection->real_escape_string($_POST['request_id']);
        $action = $connection->real_escape_string($_POST['action']);

        if ($action === 'approve') {
            // Approve the request
            $update_query = "UPDATE book_requests SET status = 'Approved' WHERE request_id = ?";
            $stmt = $connection->prepare($update_query);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $stmt->close();

            // Fetch book_id from the request
            $select_query = "SELECT book_id FROM book_requests WHERE request_id = ?";
            $stmt = $connection->prepare($select_query);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $book = $result->fetch_assoc();
            $book_id = $book['book_id'];
            $stmt->close();

            // Update books table
            $update_books_query = "UPDATE books SET is_issued = 1 WHERE book_id = ?";
            $stmt = $connection->prepare($update_books_query);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('Book request approved.'); window.location.href = 'issue_book.php';</script>";
        } elseif ($action === 'reject') {
            // Reject the request
            $update_query = "UPDATE book_requests SET status = 'Rejected' WHERE request_id = ?";
            $stmt = $connection->prepare($update_query);
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            $stmt->close();

            echo "<script>alert('Book request rejected.'); window.location.href = 'issue_book.php';</script>";
        }
    }

    $connection->close();
}
?>
