<?php
// Function to get the count of books issued to the user
function get_user_issue_book_count($connection) {
    $user_issue_book_count = 0;
    
    // Prepare the query to count approved book requests for the logged-in user
    $stmt = $connection->prepare("SELECT COUNT(*) AS user_issue_book_count FROM book_requests WHERE user_id = ? AND status = 'approved'");
    
    // Bind the user's ID (assuming it's stored in the session as 'id')
    $stmt->bind_param("i", $_SESSION['id']);
    
    // Execute the query
    $stmt->execute();
    
    // Bind the result to the variable
    $stmt->bind_result($user_issue_book_count);
    
    // Fetch the result
    $stmt->fetch();
    
    // Close the statement
    $stmt->close();
    
    // Return the count
    return $user_issue_book_count;
}


// Function to fetch available books
function get_available_books($connection) {
    $books_available = 0;
    $query = "SELECT COUNT(*) AS books_available 
              FROM books 
              LEFT JOIN issued_books ON books.book_no = issued_books.book_no 
              WHERE issued_books.book_no IS NULL";
    if ($result = $connection->query($query)) {
        if ($row = $result->fetch_assoc()) {
            $books_available = $row['books_available'];
        }
        $result->free();
    } else {
        echo "Query Error: " . $connection->error;
    }
    return $books_available;
}

// Function to get upcoming book returns for the user
function get_upcoming_returns($connection) {
    $upcoming_returns = array();
    $stmt = $connection->prepare("
        SELECT book_name, return_date
        FROM issued_books
        WHERE student_id = ? AND return_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)
    ");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $upcoming_returns[] = $row;
    }

    $stmt->close();
    return $upcoming_returns;
}


