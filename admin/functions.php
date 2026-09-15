<?php
include 'db_connect.php'; // Adjust this path if necessary

// Functions using PDO
function get_author_count() {
    global $pdo;
    $author_count = 0;
    try {
        $query = $pdo->query("SELECT COUNT(*) AS author_count FROM authors");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $author_count = $result['author_count'];
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
    }
    return $author_count;
}

function get_user_count() {
    global $pdo;
    $user_count = 0;
    try {
        $query = $pdo->query("SELECT COUNT(*) AS user_count FROM users");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $user_count = $result['user_count'];
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
    }
    return $user_count;
}

function get_book_count() {
    global $pdo;
    $book_count = 0;
    try {
        $query = $pdo->query("SELECT COUNT(*) AS book_count FROM books");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $book_count = $result['book_count'];
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
    }
    return $book_count;
}

function get_issue_book_count() {
    global $pdo;
    $issue_book_count = 0;
    try {
        $query = $pdo->query("SELECT COUNT(*) AS count FROM issued_books");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $issue_book_count = $result['count'];
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
    }
    return $issue_book_count;
}

function get_category_count() {
    global $pdo;
    $cat_count = 0;
    try {
        $query = $pdo->query("SELECT COUNT(*) AS cat_count FROM category");
        $result = $query->fetch(PDO::FETCH_ASSOC);
        $cat_count = $result['cat_count'];
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
    }
    return $cat_count;
}

// Function using MySQLi
function getUserEmailById($user_id) {
    global $conn;  // Use the global $conn variable
    
    try {
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
        if ($stmt === false) {
            throw new Exception('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if ($result === false) {
            throw new Exception('Get result failed: ' . htmlspecialchars($conn->error));
        }

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close(); // Close the prepared statement
            return $row['email'];
        } else {
            $stmt->close(); // Close the prepared statement
            return false; // Return false if user not found
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        return false; // Return false in case of error
    }
}
?>
