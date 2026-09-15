<?php
session_start();  // Start the session
include 'admin/db_connect.php';  // Adjust path as needed

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to be logged in to view this page.");
}

// Pagination variables
$limit = 20; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Fetch total number of books for pagination with search
$count_query = "SELECT COUNT(*) AS total FROM books b
                LEFT JOIN authors a ON b.author_id = a.author_id
                WHERE (b.is_issued = 0 OR b.collected_status = 'Collected') 
                AND (b.book_name LIKE '%$search%' 
                OR a.author_name LIKE '%$search%' 
                OR b.book_no LIKE '%$search%')";

$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $limit);

// Fetch books for the current page with search
$query = "SELECT b.book_id, b.book_name, b.author_id, a.author_name, b.book_no, b.book_price, b.quantity, b.collected_status 
          FROM books b
          LEFT JOIN authors a ON b.author_id = a.author_id
          WHERE (b.is_issued = 0 OR b.collected_status = 'Collected') 
          AND (b.book_name LIKE '%$search%' 
          OR a.author_name LIKE '%$search%' 
          OR b.book_no LIKE '%$search%')
          LIMIT $start, $limit";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// ... (previous code)

// Check how many books the current user has issued
$user_id = $_SESSION['id'];
$issued_count_query = "SELECT COUNT(*) AS issued_count FROM issued_books WHERE student_id = ?";
$issued_count_stmt = $conn->prepare($issued_count_query);
$issued_count_stmt->bind_param("i", $user_id);
$issued_count_stmt->execute();
$issued_count_result = $issued_count_stmt->get_result();
$issued_count = $issued_count_result->fetch_assoc()['issued_count'];

// Limit to 2 issued books
if ($issued_count >= 2) {
    echo '<div class="alert alert-warning">You cannot issue more than 2 books at a time.</div>';
    // Prevent further book issuance actions
    $can_issue = false;
} else {
    $can_issue = true;
}

// ... (remaining code)


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background-image: url(images/adm5.jpg);
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

        /* Custom styling for the button */
        .btn-secondary {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #6c757d;
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link {
            padding: 10px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Books</h1>
        
        <!-- Back Button -->
        <a href="user_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <!-- Link to the collected books page -->
        <a href="view_collected_books.php" class="btn btn-secondary mb-3">View Collected Books</a>

        <!-- Search Bar -->
        <form action="" method="GET" class="mb-3">
            <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search by Book Name, Author, or Book Number" value="<?php echo htmlspecialchars($search); ?>">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
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
                    <th>Action</th>
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
                        <td>
                            <?php if ($row['collected_status'] != 'Collected'): ?>
                                <?php if ($issued_count < 2): ?>
                                    <form action="issue_book.php" method="POST">
                                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['book_id']); ?>">
                                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">
                                        <button type="submit" class="btn btn-primary">Issue Book</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-danger" disabled>Max books issued</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>Collected</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination Controls -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                    </li>
                <?php endif; ?>

                <?php
                // Calculate the range of pages to display
                $range = 3; // Number of pages to show on either side of the current page
                $start_range = max(1, $page - $range);
                $end_range = min($total_pages, $page + $range);

                // Display the first page
                if ($start_range > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=1&search=<?php echo urlencode($search); ?>">1</a>
                    </li>
                    <?php if ($start_range > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Display the range of pages -->
                <?php for ($i = $start_range; $i <= $end_range; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Display the last page -->
                <?php if ($end_range < $total_pages): ?>
                    <?php if ($end_range < $total_pages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>"><?php echo $total_pages; ?></a>
                    </li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
