<?php
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    http_response_code(500);
    echo json_encode(['message' => 'Database connection failed']);
    exit();
}
// Set the new return date to be 3 minutes from the current time
$update_new_return_date_sql = "UPDATE book_requests 
                               SET new_return_date = DATE_ADD(NOW(), INTERVAL 3 MINUTE) 
                               WHERE request_id = ?";


$request_id = $_POST['request_id'] ?? '';
$action = $_POST['action'] ?? '';

if (empty($request_id) || empty($action)) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid input']);
    exit();
}

// Initialize response message
$response_message = '';

// Fetch the current return date and book ID from the request
$new_return_date_sql = "SELECT return_date, book_id FROM book_requests WHERE request_id = ?";
$stmt = $connection->prepare($new_return_date_sql);
$stmt->bind_param('i', $request_id);
$stmt->execute();
$stmt->bind_result($current_return_date, $book_id);
$stmt->fetch();
$stmt->close();

if ($action === 'approve') {
    $sql = "UPDATE book_requests 
            SET extend_requested = 0, 
                approval_date = NOW(), 
                status = 'Approved' 
            WHERE request_id = ?";
    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $request_id);

    if ($stmt->execute()) {
        // Update the return_date in the issued_books table
        $update_book_sql = "UPDATE issued_books 
                            SET return_date = ? 
                            WHERE book_no = ? AND student_id = (SELECT user_id FROM book_requests WHERE request_id = ?)";
        
        $update_stmt = $connection->prepare($update_book_sql);
        $update_stmt->bind_param('sii', $current_return_date, $book_id, $request_id);
        $update_stmt->execute();
        $update_stmt->close(); // Close this statement

        // Set the new return date to be 2 minutes from the current time
        $update_new_return_date_sql = "UPDATE book_requests 
                                        SET new_return_date = DATE_ADD(NOW(), INTERVAL 2 MINUTE) 
                                        WHERE request_id = ?";
        $stmt_new = $connection->prepare($update_new_return_date_sql); // Create a new statement
        $stmt_new->bind_param('i', $request_id);
        $stmt_new->execute();
        $stmt_new->close(); // Close this new statement

        $response_message = 'Extension request approved successfully';
    } else {
        http_response_code(500);
        $response_message = 'Failed to approve extension request';
    }

} elseif ($action === 'reject') {
    // Archive the request
    $archive_sql = "INSERT INTO book_requests_archive 
        (request_id, book_id, user_id, request_date, status, approved_at, return_date, book_collected, 
        approval_date, created_at, return_status, due_date, email_sent, extend_requested, new_return_date, extension_reason, is_active)
        SELECT request_id, book_id, user_id, request_date, status, approved_at, return_date, book_collected, 
        approval_date, created_at, return_status, due_date, email_sent, extend_requested, new_return_date, extension_reason, 0
        FROM book_requests WHERE request_id = ?";
    
    $stmt = $connection->prepare($archive_sql);
    $stmt->bind_param('i', $request_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Mark as rejected in the original table
        $update_sql = "UPDATE book_requests 
                       SET extend_requested = 0, 
                           approval_date = NOW(), 
                           status = 'Rejected' 
                       WHERE request_id = ?";
        $stmt = $connection->prepare($update_sql);
        $stmt->bind_param('i', $request_id);

        if ($stmt->execute()) {
            $response_message = 'Extension request rejected and archived successfully';
        } else {
            http_response_code(500);
            $response_message = 'Failed to reject extension request';
        }
    } else {
        http_response_code(500);
        $response_message = 'Failed to archive request';
    }
} else {
    http_response_code(400);
    $response_message = 'Invalid action';
}

$stmt->close();
$connection->close();

// Redirect with message
header('Location: view_extension_requests.php?message=' . urlencode($response_message));
exit();
?>
