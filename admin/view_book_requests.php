<?php
include('../admin/db_connect.php');

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pagination variables
$limit = 10; // Number of entries to show in a page. 
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$start_from = ($page - 1) * $limit;

// Fetching book requests with user names and book names
$sql = "SELECT br.*, u.name AS user_name, b.book_name
        FROM book_requests br
        JOIN users u ON br.user_id = u.id
        JOIN books b ON br.book_id = b.book_id
        WHERE br.status = 'Pending' OR br.status = 'Approved'
        ORDER BY br.request_date DESC
        LIMIT $start_from, $limit";

$result = $conn->query($sql);

// Determine the number of unread requests
$sql_unread = "SELECT COUNT(*) AS unread_count FROM book_requests 
               WHERE (status = 'Pending' OR status = 'Approved') 
               AND approval_date IS NULL";
$result_unread = $conn->query($sql_unread);
$unread_requests = $result_unread->fetch_assoc()['unread_count'];

// Query to fetch extension requests for notifications
$notification_query = "SELECT br.*, u.name AS user_name, b.book_name
                       FROM book_requests br
                       JOIN users u ON br.user_id = u.id
                       JOIN books b ON br.book_id = b.book_id
                       WHERE br.extend_requested = 1 AND br.status = 'Approved'
                       ORDER BY br.request_date DESC";
$notification_result = $conn->query($notification_query);

// Check if query was successful
if (!$notification_result) {
    die("Notification Query failed: " . $conn->error);
}

// Count number of extension requests
$notification_count = $notification_result->num_rows; // Initialize variable

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!-- HTML Block for Buttons and Title -->
<div class="container mt-5" style="text-align: center;">
    <h2>View Book Requests</h2>
    <!-- Button Container -->
    <div class="button-container mb-3">
        <!-- Back Button -->
        <a href="admin_dashboard.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

        <!-- Button to trigger auto-deletion -->
        <form action="auto_delete_requests.php" method="post" class="d-inline">
            <button type="submit" class="btn btn-delete"><i class="fas fa-trash-alt"></i> Delete Old Requests</button>
        </form>

        <!-- View Overdue Books Button -->
        <a href="view_due_books.php" class="btn btn-view"><i class="fas fa-calendar-check"></i> View Overdue Books</a>
    </div>
</div>

<?php
// Display the table if there are book requests
if ($result->num_rows > 0) {
    echo '<table class="table table-bordered mt-3">';
    echo '<thead>
            <tr>
                <th>Request ID</th>
                <th>Book Name</th>
                <th>User Name</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Return Date</th>
                <th>Book Collected</th>
                <th>Actions</th>
            </tr>
          </thead>
          <tbody id="requestTable">';
    
    while ($row = $result->fetch_assoc()) {
        $dueDate = !empty($row['return_date']) ? new DateTime($row['return_date']) : null;
        $today = new DateTime();
        $interval = $dueDate ? $today->diff($dueDate) : null;
        ?>
        <tr <?php if ($interval && $interval->days <= 3 && $row['status'] == 'Approved') echo 'class="due-soon"'; ?>>
            <td><?php echo $row['request_id']; ?></td>
            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
            <td><?php echo htmlspecialchars($row['request_date']); ?></td>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo $dueDate ? $dueDate->format('Y-m-d') : 'N/A'; ?></td>
            <td><?php echo $row['book_collected'] ? 'Yes' : 'No'; ?></td>
            <td>
                <form action="process_request.php" method="post" class="d-inline" onsubmit="return confirmAction(this);">
                    <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                    <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($row['book_name']); ?>">
                    <?php if ($row['status'] == 'Pending') { ?>
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        <br><br>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                    <?php } elseif ($row['status'] == 'Approved') { ?>
                        <?php if (!$row['book_collected']) { ?>
                            <button type="submit" name="action" value="mark_collected" class="btn btn-info btn-sm">Mark as Collected</button>
                        <?php } else { ?>
                            <button type="submit" name="action" value="mark_not_collected" class="btn btn-warning btn-sm">Mark as Not Collected</button>
                        <?php } ?>
                        <br><br>
                        <?php if ($interval && $interval->days < 0) { // Overdue ?>
                            <button type="submit" name="action" value="mark_overdue" class="btn btn-danger btn-sm">Mark as Overdue</button>
                        <?php } ?>
                    <?php } ?>
                </form>
            </td>
        </tr>
        <?php
    }
    echo '</tbody></table>';
} else {
    // Display a message or background image when no requests are found
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h3>UGHHH OH! No book requests yet.</h3>';
    echo '<p>Seems like no one is reading a book! 📚</p>';
    echo '<img src="../images/book_rqst.jpg" alt="No Requests" style="max-width: 600px; margin-top: 20px;">'; // Adjust the image path as needed
    echo '</div>';
}

// Fetch total records for pagination
$sql_total = "SELECT COUNT(*) FROM book_requests WHERE status = 'Pending' OR status = 'Approved'";
$result_total = $conn->query($sql_total);
$total_records = $result_total->fetch_array()[0];
$total_pages = ceil($total_records / $limit);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book Requests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
       body {
    margin: 0; /* Removes any default margins around the body */
    padding: 0; /* Removes any default padding around the body */
    height: 100vh; /* Ensures the body height covers the entire viewport */
    background-image: url('../images/pdf-hero.jpg'); /* Sets the background image */
    background-size: cover; /* Ensures the image covers the entire viewport */
    background-repeat: no-repeat; /* Prevents the image from repeating */
    background-position: center center; /* Centers the background image */
    background-attachment: fixed; /* Keeps the background fixed in place when scrolling */
}



        .button-container {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: center; /* Center the buttons */
        }

        .button-container .btn {
            margin: 10px;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 25px;
            text-transform: uppercase;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
        }

        .btn-back:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-delete {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background: linear-gradient(45deg, #c82333, #dc3545);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-view {
            background: linear-gradient(45deg, #ffc107, #e0a800);
            color: white;
            border: none;
        }

        .btn-view:hover {
            background: linear-gradient(45deg, #e0a800, #ffc107);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Style for the button container */
        .button-container .btn i {
            margin-right: 8px; /* Space between icon and text */
        }

        .due-soon {
            color: #dc3545;
            font-weight: bold;
        }

        .table {
            border: 2px solid black;
            border-collapse: collapse;
            background-color: white;
            color: black;
            font-size: 15px;
        }

        .table thead th {
            background-color:Black;
            color: White;
            font-size: 18px;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:nth-child(odd) {
        font-size: 18px;
           background-color: #ffffff;
        }

        .table td {
            color: black;
            font-size: 20px;
        }
        .notification-container {
    position: fixed;
    top: 10px;
    right: 10px;
}

.notification-icon {
    font-size: 24px;
    color: black;
    background-color: gold;
    border-radius: 50%;
    padding: 15px;
    width: 70px;
    height: 70px;
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s, color 0.3s, transform 0.3s;
}

.notification-icon:hover {
    background: linear-gradient(45deg, red, orange, red, red, blue, indigo, violet);
    background-size: 600% 600%;
    color: white;
    animation: rainbow 6s ease infinite, enlarge 0.3s ease-in-out;
}

@keyframes rainbow {
    0% { background-position: 0% 0%; }
    100% { background-position: 100% 100%; }
}

@keyframes enlarge {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}

@keyframes knock {
    0% {
        transform: translateX(0) rotate(0);
    }
    25% {
        transform: translateX(-10px) rotate(-10deg);
    }
    50% {
        transform: translateX(10px) rotate(10deg);
    }
    75% {
        transform: translateX(-10px) rotate(-10deg);
    }
    100% {
        transform: translateX(0) rotate(0);
    }
}

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: red;
    color: #fff;
    border-radius: 50%;
    width: 30px; /* Set a fixed width */
    height: 30px; /* Set a fixed height */
    display: flex; /* Use flexbox for centering */
    align-items: center; /* Center items vertically */
    justify-content: center; /* Center items horizontally */
    font-size: 14px; /* Adjust font size as needed */
    font-weight: bold; /* Make text bold */
}

    </style>

</head>
<body>

<!-- Notification Icon -->
<div class="notification-container" style="position: fixed; top: 10px; right: 10px;">
    <a href="view_extension_requests.php" class="notification-icon">
        <i class="fas fa-bell"></i>
        <?php
        // Fetch the count of extension requests
        $notif_query = "SELECT COUNT(*) AS count FROM book_requests WHERE extend_requested = 1";
        $notif_result = $conn->query($notif_query);
        $notif_count = $notif_result->fetch_assoc()['count'];
        if ($notif_count > 0) {
            echo "<span class='notification-count'>$notif_count</span>";
        }
        ?>
    </a>
</div>


<!-- <div class="container mt-5" style="text-align: center;">
    <h2>View Book Requests</h2>
     Back Button 
    <div class="button-container mb-3">
        Back Button
        <a href="admin_dashboard.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>

       Button to trigger auto-deletion
        <form action="auto_delete_requests.php" method="post" class="d-inline">
            <button type="submit" class="btn btn-delete"><i class="fas fa-trash-alt"></i> Delete Old Requests</button>
        </form>

         View Overdue Books Button
        <a href="view_due_books.php" class="btn btn-view"><i class="fas fa-calendar-check"></i> View Overdue Books</a>
    </div> 

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Book Name</th>
                <th>User Name</th>
                <th>Request Date</th>
                <th>Status</th>
                <th>Return Date</th>
                <th>Book Collected</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="requestTable">
        <?php while ($row = $result->fetch_assoc()) {
            $dueDate = !empty($row['return_date']) ? new DateTime($row['return_date']) : null;
            $today = new DateTime();
            $interval = $dueDate ? $today->diff($dueDate) : null;
        ?>
            <tr <?php if ($interval && $interval->days <= 3 && $row['status'] == 'Approved') echo 'class="due-soon"'; ?>>
                <td><?php echo $row['request_id']; ?></td>
                <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo $dueDate ? $dueDate->format('Y-m-d') : 'N/A'; ?></td>
                <td><?php echo $row['book_collected'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <form action="process_request.php" method="post" class="d-inline" onsubmit="return confirmAction(this);">
                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                        <input type="hidden" name="book_name" value="<?php echo htmlspecialchars($row['book_name']); ?>">
                        <?php if ($row['status'] == 'Pending') { ?>
                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                            <br><br>
                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                        <?php } elseif ($row['status'] == 'Approved') { ?>
                            <?php if (!$row['book_collected']) { ?>
                                <button type="submit" name="action" value="mark_collected" class="btn btn-info btn-sm">Mark as Collected</button>
                            <?php } else { ?>
                                <button type="submit" name="action" value="mark_not_collected" class="btn btn-warning btn-sm">Mark as Not Collected</button>
                            <?php } ?>

                            <br><br>

                            <?php if ($interval && $interval->days < 0) { // Overdue ?>
                                <button type="submit" name="action" value="mark_overdue" class="btn btn-danger btn-sm">Mark as Overdue</button>
                            <?php } ?>
                        <?php } ?>

                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>  -->
    
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "view_book_requests.php?page=".($page - 1); } ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                    <a class="page-link" href="view_book_requests.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php } ?>
            <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="<?php if($page >= $total_pages){ echo '#'; } else { echo "view_book_requests.php?page=".($page + 1); } ?>">Next</a>
            </li>
        </ul>
    </nav>
    <p>Page <?php echo $page; ?> of <?php echo $total_pages; ?></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function confirmAction(form) {
    var action = form.action.value;
    var message = "";
    if (action == "approve") {
        message = "Are you sure you want to approve this request?";
    } else if (action == "reject") {
        message = "Are you sure you want to reject this request?";
    } else if (action == "mark_collected") {
        message = "Are you sure you want to mark this book as collected?";
    } else if (action == "mark_not_collected") {
        message = "Are you sure you want to mark this book as not collected?";
    } else     if (action == "mark_not_collected") {
        message = "Are you sure you want to mark this book as not collected?";
    } else if (action == "mark_overdue") {
        message = "Are you sure you want to mark this book as overdue?";
    }
    return confirm(message);
}

// Search filter for table
$(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#requestTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

</body>
</html>

