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

// Pagination settings
$limit = 10;  // Number of books per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Connect to the database
include 'db_connect.php';  // Adjust path as needed

// Fetch users for dropdown
$user_query = "SELECT id, name FROM users";
$user_result = mysqli_query($conn, $user_query);

// Search functionality
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$search_condition = $search_term ? "AND (b.book_name LIKE '%$search_term%' OR a.author_name LIKE '%$search_term%' OR b.book_no LIKE '%$search_term%')" : '';

// User filter
$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;
$user_condition = $student_id ? "AND ib.student_id = $student_id" : '';

// Fetch books with search and filter applied
$query = "SELECT b.book_id, b.book_name, b.author_id, a.author_name, b.book_no, b.book_price, b.quantity, b.collected_status 
          FROM books b
          LEFT JOIN authors a ON b.author_id = a.author_id
          LEFT JOIN issued_books ib ON b.book_no = ib.book_no
          WHERE (b.is_issued = 0 OR b.collected_status = 'Collected') $search_condition $user_condition
          GROUP BY b.book_id
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Fetch total number of books for pagination
$total_query = "SELECT COUNT(DISTINCT b.book_id) as total FROM books b
                LEFT JOIN authors a ON b.author_id = a.author_id
                LEFT JOIN issued_books ib ON b.book_no = ib.book_no
                WHERE (b.is_issued = 0 OR b.collected_status = 'Collected') $search_condition $user_condition";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_books = $total_row['total'];
$total_pages = ceil($total_books / $limit);

// Debugging: Check if book query returned any results
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Available Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        /* Add your custom styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .book-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .book-table th, .book-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .book-table th {
            background-color: #f2f2f2;
        }

        .book-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .book-table .available {
            background-color: #e0ffe0; /* Light green for available books */
        }

        .book-table .collected {
            background-color: #ffe0e0; /* Light red for collected books */
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #007bff;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 2px;
            border-radius: 4px;
        }

        .pagination a.active {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .pagination a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin - Available Books</h1>
        
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <!-- Search Bar and User Dropdown -->
        <form action="" method="GET" class="mb-3">
            <div class="form-row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Book Name, Author, or Book Number" value="<?php echo htmlspecialchars($search_term); ?>">
                </div>
                <div class="col-md-4">
                    <select name="student_id" class="form-control">
                        <option value="0">Select User</option>
                        <?php while ($user_row = mysqli_fetch_assoc($user_result)): ?>
                            <option value="<?php echo htmlspecialchars($user_row['id']); ?>" <?php echo ($student_id == $user_row['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($user_row['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered book-table">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Book Number</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="bookTable">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="<?php echo ($row['collected_status'] == 'Collected') ? 'collected' : 'available'; ?>">
                        <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_price']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo ($row['collected_status'] == 'Collected') ? 'Collected' : 'Available'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search_term); ?>&student_id=<?php echo $student_id; ?>">« Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>&student_id=<?php echo $student_id; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search_term); ?>&student_id=<?php echo $student_id; ?>">Next »</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
