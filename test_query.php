<?php
// Database connection
$connection = new mysqli("localhost", "root", "", "lms");

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// SQL query
$sql = "SELECT * FROM books WHERE is_issued = 0";

// Execute query
$result = $connection->query($sql);

// Check if any rows are returned
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Process the rows
        echo "Book ID: " . $row["book_id"] . " - Book Name: " . $row["book_name"] . "<br>";
    }
} else {
    echo "No books available.";
}

// Close connection
$connection->close();
?>
