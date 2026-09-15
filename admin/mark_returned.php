<?php
// Include necessary files
include 'db_connect.php'; 
include 'functions.php'; // Adjust path as needed
include 'send_email.php'; // Include the email function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $request_id = isset($_POST['request_id']) ? $_POST['request_id'] : null;

    if (!$action || !$request_id) {
        die('Invalid request.');
    }

    // Check if the request_id exists
    $checkStmt = $conn->prepare("SELECT br.*, u.email, u.name, b.book_name FROM book_requests br JOIN users u ON br.user_id = u.id JOIN books b ON br.book_id = b.book_id WHERE br.request_id = ?");
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

    try {
        $conn->begin_transaction(); // Start transaction

        if ($action === 'approve') {
            // Set return date to 3 minutes from now
            $return_date = date('Y-m-d H:i:s', strtotime('+3 minutes'));
            $stmt = $conn->prepare("UPDATE book_requests SET status = 'Approved', approved_at = NOW(), return_date = ? WHERE request_id = ?");
            $stmt->bind_param("si", $return_date, $request_id);

            if ($stmt->execute()) {
                // Update books table to mark book as issued
                $updateBookStmt = $conn->prepare("UPDATE books b JOIN book_requests br ON b.book_id = br.book_id SET b.is_issued = 1 WHERE br.request_id = ?");
                $updateBookStmt->bind_param("i", $request_id);
                $updateBookStmt->execute();
                $updateBookStmt->close();

                // Prepare and send the styled email
                $subject = "Book Request Approved - $bookName";
                sendStyledEmail($userEmail, $subject, $bookName, $return_date, $userName);
            } else {
                throw new Exception("Error approving request: " . $stmt->error);
            }
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE book_requests SET status = 'Rejected' WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();
            // Removed email sending code
        } elseif ($action === 'mark_collected') {
            $stmt = $conn->prepare("UPDATE book_requests SET book_collected = 1 WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            $updateBookStmt = $conn->prepare("UPDATE books b JOIN book_requests br ON b.book_id = br.book_id SET b.collected_status = 'Collected' WHERE br.request_id = ?");
            $updateBookStmt->bind_param("i", $request_id);
            $updateBookStmt->execute();
            $updateBookStmt->close();
        } elseif ($action === 'mark_not_collected') {
            $stmt = $conn->prepare("UPDATE book_requests SET book_collected = 0 WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            $updateBookStmt = $conn->prepare("UPDATE books b JOIN book_requests br ON b.book_id = br.book_id SET b.collected_status = 'Not Collected' WHERE br.request_id = ?");
            $updateBookStmt->bind_param("i", $request_id);
            $updateBookStmt->execute();
            $updateBookStmt->close();
        } elseif ($action === 'return_book') {
            // Start transaction
            $conn->begin_transaction(); 
            try {
                // Update the status and return date of the book request
                $stmt = $conn->prepare("UPDATE book_requests SET return_status = 'Returned', return_date = NOW() WHERE request_id = ?");
                $stmt->bind_param("i", $request_id);
                $stmt->execute();
                
                // Update the corresponding entry in the books table
                $updateBookStmt = $conn->prepare("UPDATE books b JOIN book_requests br ON b.book_id = br.book_id SET b.is_issued = 0, b.collected_status = 'Not Collected' WHERE br.request_id = ?");
                $updateBookStmt->bind_param("i", $request_id);
                $updateBookStmt->execute();
                $updateBookStmt->close();
        
                // Delete the book request from the book_requests table
                $deleteStmt = $conn->prepare("DELETE FROM book_requests WHERE request_id = ?");
                $deleteStmt->bind_param("i", $request_id);
                $deleteStmt->execute();
                $deleteStmt->close();
        
                // Commit transaction
                $conn->commit(); 
            } catch (Exception $e) {
                // Rollback transaction on error
                $conn->rollback(); 
                error_log($e->getMessage()); // Log the error message
                die('An error occurred. Please try again later.');
            } finally {
                // Close the prepared statements
                if (isset($stmt)) {
                    $stmt->close();
                }
                if (isset($deleteStmt)) {
                    $deleteStmt->close();
                }
            }
        }
         elseif ($action === 'mark_overdue') {
            $stmt = $conn->prepare("UPDATE book_requests SET status = 'Overdue' WHERE request_id = ?");
            $stmt->bind_param("i", $request_id);
            $stmt->execute();

            // Send overdue email notification
            $subject = "Book Request Overdue - $bookName";
            sendStyledEmail($userEmail, $subject, $bookName, null, $userName);
        } else {
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
