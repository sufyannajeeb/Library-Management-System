<?php

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['cat_id'])) {
    $cat_id = $_POST['cat_id'];

    // Fetch books based on selected category
    $query = "SELECT books.book_name, authors.author_name FROM books 
              JOIN authors ON books.author_id = authors.author_id 
              WHERE books.cat_id = '$cat_id'";
    $result = mysqli_query($connection, $query);
    
    // Count the number of books in the selected category
    $book_count = mysqli_num_rows($result);

    if ($book_count > 0) {
        echo "<tr><td colspan='2'><strong>Total Books in this Category: $book_count</strong></td></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . htmlspecialchars($row['book_name']) . "</td><td>" . htmlspecialchars($row['author_name']) . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No books found in this category.</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>Please select a category.</td></tr>";
}

mysqli_close($connection);
?>
