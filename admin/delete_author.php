<?php
ob_start();

    $connection = mysqli_connect("localhost","root","");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    $db = mysqli_select_db($connection,"lms");
    if (!$db) {
        die("Database selection failed: " . mysqli_error($connection));
    }
    
    $author_id = $_GET['aid'];
    $query = "DELETE FROM authors WHERE author_id = $author_id";
    $query_run = mysqli_query($connection, $query);
    
    if ($query_run) {
        echo "<script>alert('Author deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete author.');</script>";
    }
?>
<script type="text/javascript">
    window.location.href = "manage_author.php";
</script>


