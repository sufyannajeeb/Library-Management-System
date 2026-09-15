<?php
function get_connection() {
    $connection = new mysqli("localhost", "root", "", "lms");
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    return $connection;
}

function get_user_count() {
    $connection = get_connection();
    $query = "SELECT COUNT(*) AS count FROM users";
    $result = $connection->query($query);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}

function get_book_count() {
    $connection = get_connection();
    $query = "SELECT COUNT(*) AS count FROM books";
    $result = $connection->query($query);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}

function get_category_count() {
    $connection = new mysqli("localhost", "root", "", "lms");
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    $sql = "SELECT COUNT(*) AS count FROM category"; // Corrected table name
    $result = $connection->query($sql);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}


function get_author_count() {
    $connection = get_connection();
    $query = "SELECT COUNT(*) AS count FROM authors";
    $result = $connection->query($query);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}

function get_issue_book_count() {
    $connection = get_connection();
    $query = "SELECT COUNT(*) AS count FROM issued_books";
    $result = $connection->query($query);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}

function get_book_requests() {
    $connection = get_connection();
    $sql = "SELECT r.request_id, r.book_id, r.user_id, r.request_date, r.status, b.book_name
            FROM book_requests r
            JOIN books b ON r.book_id = b.book_id";
    $result = $connection->query($sql);
    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
    $connection->close();
    return $requests;
}

function update_book_request_status($request_id, $status) {
    $connection = get_connection();
    $sql = "UPDATE book_requests SET status = ? WHERE request_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $status, $request_id);
    $result = $stmt->execute();
    $stmt->close();
    $connection->close();
    return $result;
}

function get_pending_request_count() {
    $connection = get_connection();
    $query = "SELECT COUNT(*) AS count FROM book_requests WHERE status = 'pending'";
    $result = $connection->query($query);
    $data = $result->fetch_assoc();
    $connection->close();
    return $data['count'];
}

function approve_book_request($request_id) {
    $connection = get_connection();
    // Fetch book_id and user_id
    $sql = "SELECT book_id, user_id FROM book_requests WHERE request_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();
    if ($request) {
        $book_id = $request['book_id'];
        $user_id = $request['user_id'];
        // Update quantity in books table
        $sql = "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        // Add to issued_books
        $sql = "INSERT INTO issued_books (book_id, user_id, issue_date) VALUES (?, ?, NOW())";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $book_id, $user_id);
        $stmt->execute();
    }
    $stmt->close();
    $connection->close();
}
?>
