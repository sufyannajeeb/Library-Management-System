<?php
ob_start();
    $connection = mysqli_connect("localhost", "root", "", "lms");

    // Check connection
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Check if the 'bn' parameter is set in the URL
    if (isset($_GET['bn'])) {
        $book_no = $_GET['bn'];

        // Escape the input to prevent SQL injection
        $book_no = mysqli_real_escape_string($connection, $book_no);

        // Delete the book from the database
        $query = "DELETE FROM books WHERE book_no = '$book_no'";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            echo "<script>alert('Book deleted successfully.');</script>";
        } else {
            echo "<script>alert('Failed to delete book.');</script>";
        }
    } else {
        echo "<script>alert('Book number parameter not set.');</script>";
    }
?>
<script type="text/javascript">
    window.location.href = "manage_book.php";
</script>
