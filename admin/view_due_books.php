<?php
session_start(); // Start session to use session variables
include('../admin/db_connect.php');
include('send_notify_email.php');

$conn = $conn ?? null;

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$return_date = date('Y-m-d H:i:s'); // Current date and time

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null;

    if (!$action || !$request_id) {
        error_log("Action: $action, Request ID: $request_id"); // Log the values for debugging
        die('Invalid request.');
    }

    // Handle form submission for marking books as returned
    if ($action === 'mark_returned') {
        try {
            $conn->begin_transaction(); // Start transaction

            // Update book request with the current date and time for return_date
            $stmt = $conn->prepare("UPDATE book_requests SET return_status = 'Returned', return_date = NOW() WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);

            if ($stmt->execute()) {
                // Update the books table to mark the book as not issued and not collected
                $updateBookStmt = $conn->prepare("UPDATE books b JOIN book_requests br ON b.book_id = br.book_id SET b.is_issued = 0, b.collected_status = 'Available' WHERE br.request_id = ?");
                $updateBookStmt->bind_param("i", $request_id);

                if ($updateBookStmt->execute()) {
                    // Set success message in session
                    $_SESSION['return_message'] = "Book has been marked as returned successfully.";

                    // Fetch the book ID for notifications
                    $bookIdQuery = "SELECT book_id FROM book_requests WHERE request_id = ?";
                    $bookIdStmt = $conn->prepare($bookIdQuery);
                    $bookIdStmt->bind_param("i", $request_id);
                    $bookIdStmt->execute();
                    $bookIdResult = $bookIdStmt->get_result();
                    $bookRow = $bookIdResult->fetch_assoc();
                    $book_id = $bookRow['book_id'];

                    // Notify users if the book is available
// Notify users if the book is available
$notifyQuery = "SELECT n.user_id, u.email, u.name FROM notifications n JOIN users u ON n.user_id = u.id WHERE n.book_id = ? AND n.notified = 0";
$notifyStmt = $conn->prepare($notifyQuery);
$notifyStmt->bind_param("i", $book_id);
$notifyStmt->execute();
$notifyResult = $notifyStmt->get_result();

while ($notifyRow = $notifyResult->fetch_assoc()) {
    $userEmail = $notifyRow['email'];
    $userName = $notifyRow['name'];

    // Fetch the book name
    $bookNameQuery = "SELECT book_name FROM books WHERE book_id = ?";
    $bookNameStmt = $conn->prepare($bookNameQuery);
    $bookNameStmt->bind_param("i", $book_id);
    $bookNameStmt->execute();
    $bookNameResult = $bookNameStmt->get_result();
    $bookNameRow = $bookNameResult->fetch_assoc();
    $bookName = $bookNameRow['book_name'];

    // Send notification email
    sendNotifyEmail($userEmail, 'Book Available Notification', $bookName, $userName);

    // Mark notification as sent
    $updateNotifyStmt = $conn->prepare("UPDATE notifications SET notified = 1 WHERE user_id = ? AND book_id = ?");
    $updateNotifyStmt->bind_param("ii", $notifyRow['user_id'], $book_id);
    $updateNotifyStmt->execute();
}

// After notifying users, if all notifications for this book have been sent, you may want to consider deleting them
// After notifying users, if all notifications for this book have been sent, you may want to consider deleting them
$checkNotificationQuery = "
    SELECT COUNT(*) as count 
    FROM notifications 
    WHERE book_id = ? AND notified = 0"; // Check for notifications not yet sent

$checkNotificationStmt = $conn->prepare($checkNotificationQuery);
$checkNotificationStmt->bind_param("i", $book_id);
$checkNotificationStmt->execute();
$notificationResult = $checkNotificationStmt->get_result();
$notificationRow = $notificationResult->fetch_assoc();

// If all notifications are marked as notified, you can choose to delete them
if ($notificationRow['count'] === 0) { // Change to check for zero (no pending notifications)
    $deleteRequestsQuery = "
        DELETE FROM book_requests 
        WHERE book_id = ?"; // Deletes all requests for this book
    $deleteRequestsStmt = $conn->prepare($deleteRequestsQuery);
    $deleteRequestsStmt->bind_param("i", $book_id);
    $deleteRequestsStmt->execute();
}




                    $conn->commit(); // Commit transaction
                } else {
                    throw new Exception("Error updating books table: " . $updateBookStmt->error);
                }
            } else {
                throw new Exception("Error updating book request: " . $stmt->error);
            }

            $stmt->close();
            $updateBookStmt->close();
            $bookIdStmt->close();
            $notifyStmt->close();
        } catch (Exception $e) {
            $conn->rollback(); // Rollback transaction on error
            error_log($e->getMessage());
            die('An error occurred. Please try again later.');
        }

        header('Location: view_due_books.php');
        exit();
    } else {
        die('Invalid action.');
    }
}

// Updated SQL to check for both return dates
$sql_due_books = "
    SELECT br.*, u.name AS user_name, b.book_name, br.approved_at, br.new_return_date
    FROM book_requests br
    JOIN users u ON br.user_id = u.id
    JOIN books b ON br.book_id = b.book_id
    WHERE br.status = 'Approved' 
      AND (
        (br.new_return_date IS NOT NULL AND br.new_return_date < NOW()) -- New return date has passed
        OR
        (br.new_return_date IS NULL AND br.return_date < NOW())        -- Original return date has passed and no new return date
      )
";

// Fetch the count of extension requests once
$notif_query = "SELECT COUNT(*) AS count FROM book_requests WHERE extend_requested = 1";
$notif_result = $conn->query($notif_query);
$notif_count = $notif_result->fetch_assoc()['count'];

$result_due_books = $conn->query($sql_due_books);

if ($conn) {
    if (!$result_due_books) {
        die("Due books query failed: " . $conn->error);
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Due Books</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .due-books-table {
            overflow-x: auto;
        }
        .overdue {
            color: #dc3545;
            font-weight: bold;
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
    background: linear-gradient(45deg, red, orange, yellow, blue, indigo, violet);
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

.notification-count {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: red;
    color: #fff;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}

/* Container for buttons */
.button-container {
    margin-top: 20px;
    margin-bottom: 20px;
    text-align: center; /* Center the buttons */
}

/* General button styling */
.button-container .btn {
    margin: 10px;
    padding: 15px 30px;
    font-size: 16px;
    border-radius: 25px;
    text-transform: uppercase;
    font-weight: bold;
    transition: all 0.3s ease;
}

/* Back Button Styling */
.btn-back {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border: none;
}

.btn-back:hover {
    background: linear-gradient(45deg, #0056b3, #007bff);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Delete Button Styling */
.btn-delete {
    background: linear-gradient(45deg, #dc3545, #c82333);
    color: white;
    border: none;
}

.btn-delete:hover {
    background: linear-gradient(45deg, #c82333, #dc3545);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* View Button Styling */
.btn-view {
    background: linear-gradient(45deg, #ffc107, #e0a800);
    color: white;
    border: none;
}

.btn-view:hover {
    background: linear-gradient(45deg, #e0a800, #ffc107);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Button Icon Spacing */
.button-container .btn i {
    margin-right: 8px; /* Space between icon and text */
}


    </style>
</head>
<body style="background-image: url('../images/due_book.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center; height: 100vh;">


<!-- HTML Block for Buttons and Title -->
<div class="container mt-5" style="text-align: center;">
    <h2>View Over Due Requests</h2>
    <!-- Button Container -->
    <div class="button-container mb-3">
        <!-- Back Button -->
        <a href="view_book_requests.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Book Request</a>

        <!-- Button to trigger auto-deletion -->
        <form action="auto_delete_requests.php" method="post" class="d-inline">
            <button type="submit" class="btn btn-delete"><i class="fas fa-trash-alt"></i> Delete Old Requests</button>
        </form>

        

       <!-- Add this inside your admin section -->
<form method="POST" action="send_reminders.php" class="d-inline" onsubmit="return confirm('Are you sure you want to remind users about overdue books?');">
    <input type="hidden" name="action" value="remind_users">
    <button type="submit" name="remind_users" class="btn btn-warning"><i class="fas fa-bell"></i> Remind Users</button>
</form>

    </div>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const hasNewNotifications = <?php echo json_encode($has_new_notifications); ?>;

    if (hasNewNotifications === 'true') {
        const icon = document.querySelector('.notification-icon');
        icon.classList.add('shake');

        // Remove the shake animation when the notification is opened
        document.querySelector('.notification-icon').addEventListener('click', function () {
            icon.classList.remove('shake');
        });
    }
});
</script>



<div class="container mt-5">
    <h2>Overdue Books</h2>
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">Back to Dashboard</a>

    <div class="due-books-table">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Book Name</th>
                    <th>User Name</th>
                    <th>Request Date</th>
                    <th>Approved At</th>
                    <th>Current Return Date</th>
                    <th>New Return Date</th>
                    <th>Actions</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_due_books): while ($row_due = $result_due_books->fetch_assoc()) { ?>
                    <tr class="overdue">
                        <td><?php echo $row_due['request_id']; ?></td>
                        <td><?php echo htmlspecialchars($row_due['book_name']); ?></td>
                        <td><?php echo htmlspecialchars($row_due['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($row_due['request_date']); ?></td>
                        <td><?php echo htmlspecialchars($row_due['approved_at']); ?></td>
                        <td><?php echo htmlspecialchars($row_due['return_date']); ?></td>
                        <td><?php echo $row_due['new_return_date'] ? htmlspecialchars($row_due['new_return_date']) : 'Not Requested Yet'; ?></td>
                        <td>
    <!-- Existing "Mark as Returned" button -->
    <form action="view_due_books.php" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to mark this book as returned?');">
        <input type="hidden" name="request_id" value="<?php echo $row_due['request_id']; ?>">
        <input type="hidden" name="action" value="mark_returned">
        <button type="submit" class="btn btn-success btn-sm">Mark as Returned</button>
    </form>
    <br><br>
   <!-- Payment Verification Switch -->
   <td>
    <label class="switch">
        <input type="checkbox" id="paymentToggle_<?php echo $row_due['request_id']; ?>" 
            onchange="togglePayment(<?php echo $row_due['request_id']; ?>, this)"
            <?php echo $row_due['payment_verified'] ? 'checked' : ''; ?> />
        <span class="slider round"></span>
    </label>
    <p id="paymentStatusText_<?php echo $row_due['request_id']; ?>"><?php echo $row_due['payment_verified'] ? 'Payment Verified' : 'Payment Not Verified'; ?></p>
</td>


<style>
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #28a745; /* Green color for verified */
}

input:checked + .slider:before {
    transform: translateX(26px);
}
</style>


<?php if (isset($_SESSION['return_message'])): ?>
    <div class="alert alert-success mt-3">
        <?php
            echo $_SESSION['return_message']; // Display the message
            unset($_SESSION['return_message']); // Clear the message after displaying
        ?>
    </div>
<?php endif; ?>


<script>
// On page load, check local storage and set the switch state
window.onload = function() {
    const requestId = <?php echo json_encode($row_due['request_id']); ?>;

    // Get the toggle and status text elements for the current row
    const paymentToggle = document.getElementById(`paymentToggle_${requestId}`);
    const paymentStatusText = document.getElementById(`paymentStatusText_${requestId}`);

    // Check local storage for saved state
    const storedPaymentState = localStorage.getItem(`payment_verified_${requestId}`);
    if (storedPaymentState !== null) {
        paymentToggle.checked = storedPaymentState === 'true';
    } else {
        // Fallback to the initial state from PHP if not in local storage
        const initialPaymentState = <?php echo json_encode($row_due['payment_verified']); ?>;
        paymentToggle.checked = initialPaymentState;
    }

    // Update the payment status text
    paymentStatusText.innerText = paymentToggle.checked ? 'Payment Verified' : 'Payment Not Verified';
};

function togglePayment(requestId, toggle) {
    const action = toggle.checked ? 'verify' : 'unverify';
    const paymentStatusText = document.getElementById(`paymentStatusText_${requestId}`);

    // Prepare AJAX request
    fetch('toggle_payment.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            request_id: requestId,
            payment_action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update local storage with the new state
            localStorage.setItem(`payment_verified_${requestId}`, toggle.checked);
            paymentStatusText.innerText = toggle.checked ? 'Payment Verified' : 'Payment Not Verified';
            alert(`Payment has been ${toggle.checked ? 'verified' : 'unverified'} successfully!`);
        } else {
            alert('Error: ' + data.error);
            toggle.checked = !toggle.checked; // Revert the toggle state on error
        }
    })
    .catch(error => console.error('Error:', error));
}


</script>






</td>

                    </tr>
                <?php } endif; ?>
            </tbody>
        </table>
    </div>
</div>


</body>
</html>
