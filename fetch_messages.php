<?php
session_start();
$connection = mysqli_connect("localhost", "root", "", "lms");

$sender_id = $_SESSION['user_id'];
$receiver_id = mysqli_real_escape_string($connection, $_GET['receiver_id']);

$query = "SELECT * FROM messages WHERE 
          (sender_id = '$sender_id' AND receiver_id = '$receiver_id') OR 
          (sender_id = '$receiver_id' AND receiver_id = '$sender_id')
          ORDER BY timestamp ASC";

$result = mysqli_query($connection, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $message = htmlspecialchars($row['message']);
    $is_sender = $row['sender_id'] == $sender_id;
    echo "<div class='" . ($is_sender ? "sent" : "received") . "'>$message</div>";
}

mysqli_close($connection);
?>
