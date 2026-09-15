<?php
session_start();
include 'admin/db_connect.php';  // Adjust path as needed

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to be logged in to perform this action.");
}

// Fetch collected books excluding those borrowed by the current user
$userId = $_SESSION['id'];

// Prepare the query to exclude books borrowed or collected by the current user
$query = "SELECT b.book_id, b.book_name, a.author_name, b.book_no, b.book_price, b.quantity, b.collected_status
          FROM books b
          LEFT JOIN authors a ON b.author_id = a.author_id
          LEFT JOIN book_requests br ON b.book_id = br.book_id
          WHERE b.collected_status = 'Collected' 
            AND br.user_id != ?";

// Prepare and execute the query
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();

// Get the result
$result = $stmt->get_result();
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}


// Add notification request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_id'])) {
    $bookId = $_POST['book_id'];
    $userId = $_SESSION['id'];

    // Delete existing notification request if it exists
    $delete_query = "DELETE FROM notifications WHERE user_id = ? AND book_id = ?";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bind_param("ii", $userId, $bookId);
    $delete_stmt->execute();
    $delete_stmt->close();

    // Insert a new notification request
    $insert_query = "INSERT INTO notifications (user_id, book_id, notification_type, notified, created_at) VALUES (?, ?, 'Availability', 0, NOW())";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ii", $userId, $bookId);

    if ($insert_stmt->execute()) {
        // Fetch user details
        $user_query = "SELECT email, name FROM users WHERE id = ?";
        $user_stmt = $conn->prepare($user_query);
        $user_stmt->bind_param("i", $userId);
        $user_stmt->execute();
        $user_row = $user_stmt->get_result()->fetch_assoc();
        $email = $user_row['email'];
        $user_name = $user_row['name'];

        // Fetch book details
        $book_query = "SELECT book_name FROM books WHERE book_id = ?";
        $book_stmt = $conn->prepare($book_query);
        $book_stmt->bind_param("i", $bookId);
        $book_stmt->execute();
        $book_row = $book_stmt->get_result()->fetch_assoc();
        $book_name = $book_row['book_name'];

        // Send confirmation email
        $subject = "Book Availability Notification Request";
        try {
            sendNotificationConfirmationEmail($email, $subject, $book_name, $user_name);
        } catch (Exception $e) {
            echo "Failed to send notification email.";
            error_log("Error sending email: " . $e->getMessage());
        }

        // Respond with success message
        echo json_encode(['message' => "You will be notified when this book becomes available."]);
    } else {
        echo json_encode(['message' => "Failed to request notification."]);
    }

    // Close statements and connection
    $insert_stmt->close();
    $conn->close();
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collected Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            max-width: 1200px;
        }
        .table th, .table td {
            text-align: center;
        }
        .btn-info {
            transition: background-color 0.3s;
        }
        .btn-secondary {
            cursor: not-allowed;
        }
        .notification-message {
            margin-top: 10px;
            font-style: italic;
            color: #28a745; /* Green color for success message */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Collected Books</h1>

        <!-- Back Button -->
        <a href="view_available_books.php" class="btn btn-primary mb-3">Back to Available Books</a>

        <table class="table table-bordered">
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
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['book_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($row['collected_status']); ?></td>
                            <td>
                                <form class="d-inline" onsubmit="return notifyMe(<?php echo htmlspecialchars($row['book_id']); ?>, this);">
                                    <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['book_id']); ?>">
                                    <button type="submit" class="btn btn-info">Notify Me</button>
                                    <div class="notification-message" id="message-<?php echo htmlspecialchars($row['book_id']); ?>"></div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No collected books available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function notifyMe(bookId, form) {
            $.ajax({
                type: 'POST',
                url: 'notify_user.php', // Ensure this URL is correct
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    var messageElement = $('#message-' + bookId);
                    messageElement.text(response.message);
                    // Optionally update button text or disable button here
                },
                error: function() {
                    alert('Error occurred.');
                }
            });
            return false; // Prevent the form from submitting the traditional way
        }
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
