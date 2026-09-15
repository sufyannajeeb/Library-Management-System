<?php
session_start();  // Start the session
include 'admin/db_connect.php';  // Adjust path as needed

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    die("You need to be logged in to issue a book.");
}

// Validate and sanitize input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
    $user_id = $_SESSION['id'];

    if ($book_id <= 0) {
        die("Invalid book ID.");
    }

    // Check if the book is available
    $query = "SELECT quantity, is_issued FROM books WHERE book_id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $book_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $book = mysqli_fetch_assoc($result);

        if (!$book || $book['is_issued'] || $book['quantity'] <= 0) {
            die("The book is not available for issuing.");
        }
        mysqli_stmt_close($stmt);
    } else {
        die("Error checking book availability: " . mysqli_error($conn));
    }

    // Insert new book request with status 'Pending'
    $query = "INSERT INTO book_requests (book_id, user_id, request_date, status) 
              VALUES (?, ?, NOW(), 'Pending')";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ii", $book_id, $user_id);
        if (mysqli_stmt_execute($stmt)) {
            // Optionally, you can redirect or provide feedback
            header("Location: view_available_books.php?status=success");
            exit();
        } else {
            error_log("Error executing query: " . mysqli_error($conn));
            die("An error occurred while submitting your request. Please try again later.");
        }
        mysqli_stmt_close($stmt);
    } else {
        error_log("Error preparing statement: " . mysqli_error($conn));
        die("An error occurred while processing your request. Please try again later.");
    }

    mysqli_close($conn);
} else {
    die("Invalid request method.");
}
?>
