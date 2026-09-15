<?php
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$search_query = "";
if (isset($_POST['query'])) {
    $search_query = mysqli_real_escape_string($connection, $_POST['query']);
}

$query = "SELECT * FROM users WHERE 
          name LIKE '%$search_query%' OR 
          email LIKE '%$search_query%' OR 
          mobile LIKE '%$search_query%' OR 
          address LIKE '%$search_query%'";

$query_run = mysqli_query($connection, $query);

if (mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
        echo "
        <tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['mobile']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['address']) . "</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No users found.</td></tr>";
}

mysqli_close($connection);
?>
