<?php
// Database connection
$host = 'localhost';  // Replace with your host
$db = 'lms';          // Replace with your database name
$user = 'root';       // Replace with your database username
$pass = '';           // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch most used books and their request count
$sql = "
    SELECT 
        b.book_id,
        b.book_name AS book_title,
        a.author_name AS author_name,
        c.cat_name AS category_name,
        COUNT(brh.book_id) AS request_count
    FROM 
        books b
    JOIN 
        authors a ON b.author_id = a.author_id
    JOIN 
        category c ON b.cat_id = c.cat_id 
    LEFT JOIN 
        book_request_history brh ON b.book_id = brh.book_id
    GROUP BY 
        b.book_id
    ORDER BY 
        request_count DESC
    LIMIT 4;
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Most Used Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
        }

        .rank {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            flex-direction: column; /* Stack items vertically */
            text-align: center; /* Center text */
        }

        .rank:nth-of-type(1) {
            background-color: gold; /* 1st Place */
        }

        .rank:nth-of-type(2) {
            background-color: #c0c0c0; /* 2nd Place */
        }

        .rank:nth-of-type(3) {
            background-color: #cd7f32; /* 3rd Place */
        }

        .rank:nth-of-type(4),
        .rank:nth-of-type(5),
        .rank:nth-of-type(6) {
            background-color: #f5f5f5; /* 4th, 5th, 6th Places */
        }

        .rank:hover {
            transform: scale(1.05);
        }

        .rank div {
            margin: 5px 0; /* Add vertical spacing between items */
        }
    </style>
</head>
<body>

<button onclick="history.back()" style="margin: 20px; padding: 10px 20px; font-size: 16px; cursor: pointer; border: none; border-radius: 5px; background-color: #007BFF; color: white;">
    &larr; Back
</button>


<div class="container" id="rankingContainer">
    <h2>Most Frequently Used Books</h2>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        $position = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<div class='rank'>
                    <div>Position: {$position}</div>
                    <div>{$row['book_title']}</div>
                    <div>Author: {$row['author_name']}</div>
                    <div>Times Students Borrowed: {$row['request_count']}</div> <!-- Display request count -->
                  </div>";
            $position++;
        }
    } else {
        echo "<div class='rank'>
                <div>No books have been requested yet. 🤷‍♂️</div>
                <div>Don't worry, they'll be flying off the shelves soon! 🚀</div>
              </div>";
    }
    ?>
</div>

<?php
$conn->close();
?>
</body>
</html>
