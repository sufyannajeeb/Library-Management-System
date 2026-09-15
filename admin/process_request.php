<?php
// Include necessary files
include 'db_connect.php'; 
include 'functions.php'; // Adjust path as needed
include 'send_email.php'; // Include the email function
include 'send_reject_email.php';
include 'send_availability_email.php'; // Include the availability email function
include 'send_confirmatiion_email.php'; // Include the confirmation email function

$return_date = date('Y-m-d H:i:s'); // Current date and time

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $request_id = isset($_POST['request_id']) ? (int)$_POST['request_id'] : null;

    if (!$action || !$request_id) {
        die('Invalid request.');
    }

    // Check if the request_id exists
    $checkStmt = $conn->prepare("
        SELECT br.*, u.email, u.name, b.book_name, b.book_id
        FROM book_requests br 
        JOIN users u ON br.user_id = u.id 
        JOIN books b ON br.book_id = b.book_id 
        WHERE br.request_id = ?
    ");
    $checkStmt->bind_param("i", $request_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows == 0) {
        die('Request not found or has been deleted.');
    }

    $request = $result->fetch_assoc();
    $userEmail = $request['email']; 
    $userName = $request['name'];
    $bookName = $request['book_name'];
    $bookId = $request['book_id'];

    try {
        $conn->begin_transaction(); // Start transaction

        switch ($action) {
            case 'approve':
                // Set approval date to the current time
                $approval_date = date('Y-m-d H:i:s');
                
                // Ensure the approval time is always after the request date
                if (strtotime($approval_date) < strtotime($request['request_date'])) {
                    $approval_date = $request['request_date'];
                }
            
                // Set return date to 2 minutes from the approval date
                $return_date = date('Y-m-d H:i:s', strtotime($approval_date . ' + 2 minutes'));
            
                $stmt = $conn->prepare("
                    UPDATE book_requests 
                    SET status = 'Approved', approved_at = ?, return_date = ? 
                    WHERE request_id = ?
                ");
                $stmt->bind_param("ssi", $approval_date, $return_date, $request_id);
            
                if ($stmt->execute()) {
                    // Update books table to mark book as issued
                    $updateBookStmt = $conn->prepare("
                        UPDATE books b 
                        JOIN book_requests br ON b.book_id = br.book_id 
                        SET b.is_issued = 1 
                        WHERE br.request_id = ?
                    ");
                    $updateBookStmt->bind_param("i", $request_id);
                    $updateBookStmt->execute();
                    $updateBookStmt->close();

                    // Prepare and send the styled email for approval
                    $subject = "Book Request Approved - $bookName";
                    sendStyledEmail($userEmail, $subject, $bookName, $return_date, $userName);
                } else {
                    throw new Exception("Error approving request: " . $stmt->error);
                }
                break;

            case 'reject':
                // Update the request status to rejected
                $stmt = $conn->prepare("
                    UPDATE book_requests 
                    SET status = 'Rejected' 
                    WHERE request_id = ?
                ");
                $stmt->bind_param("i", $request_id);
                if ($stmt->execute()) {
                    // Prepare and send the rejection email
                    $subject = "Book Request Rejected - $bookName";
                    sendRejectEmail($userEmail, $subject, $bookName, $userName);
                } else {
                    throw new Exception("Error rejecting request: " . $stmt->error);
                }
                break;

            case 'mark_collected':
                // Mark book as collected
                $stmt = $conn->prepare("
                    UPDATE book_requests 
                    SET book_collected = 1 
                    WHERE request_id = ?
                ");
                $stmt->bind_param("i", $request_id);
                $stmt->execute();

                $updateBookStmt = $conn->prepare("
                    UPDATE books b 
                    JOIN book_requests br ON b.book_id = br.book_id 
                    SET b.collected_status = 'Collected' 
                    WHERE br.request_id = ?
                ");
                $updateBookStmt->bind_param("i", $request_id);
                $updateBookStmt->execute();
                $updateBookStmt->close();
                break;

            case 'mark_not_collected':
                // Mark book as not collected
                $stmt = $conn->prepare("
                    UPDATE book_requests 
                    SET book_collected = 0 
                    WHERE request_id = ?
                ");
                $stmt->bind_param("i", $request_id);
                $stmt->execute();

                $updateBookStmt = $conn->prepare("
                    UPDATE books b 
                    JOIN book_requests br ON b.book_id = br.book_id 
                    SET b.collected_status = 'Available' 
                    WHERE br.request_id = ?
                ");
                $updateBookStmt->bind_param("i", $request_id);
                $updateBookStmt->execute();
                $updateBookStmt->close();
                break;

                case 'return_book':
                    // Define the return message
                    $returnMessage = "Book has been marked as returned.";
                    
                    // Update the book request with return status and message
                    $stmt = $conn->prepare("
                        UPDATE book_requests 
                        SET return_status = 'Returned', return_date = NOW(), return_message = ? 
                        WHERE request_id = ?
                    ");
                    $stmt->bind_param("si", $returnMessage, $request_id);
                    if (!$stmt->execute()) {
                        throw new Exception("Error updating return status: " . $stmt->error);
                    }
                    $stmt->close();
                    
                    // Update book status
                    $updateBookStmt = $conn->prepare("
                        UPDATE books b 
                        JOIN book_requests br ON b.book_id = br.book_id 
                        SET b.is_issued = 0, b.collected_status = 'Not Collected' 
                        WHERE br.request_id = ?
                    ");
                    $updateBookStmt->bind_param("i", $request_id);
                    if (!$updateBookStmt->execute()) {
                        throw new Exception("Error updating book status: " . $updateBookStmt->error);
                    }
                    $updateBookStmt->close();
                    break;
                

            default:
                throw new Exception('Invalid action.');
        }

        $conn->commit(); // Commit transaction
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaction on error
        error_log($e->getMessage()); // Log the error message
        die('An error occurred. Please try again later.');
    }

    // Close the prepared statements
    $stmt->close();
    $checkStmt->close();
    $conn->close();

    // Redirect to avoid form resubmission warning
    header('Location: view_book_requests.php');
    exit();
}
?>
