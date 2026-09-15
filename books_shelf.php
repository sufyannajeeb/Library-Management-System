<?php
// Database connection
$host = 'localhost'; // Replace with your host
$db = 'lms';         // Replace with your database name
$user = 'root';      // Replace with your database username
$pass = '';          // Replace with your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "
    SELECT c.cat_id, c.cat_name, b.book_id, b.book_name
    FROM category c
    LEFT JOIN books b ON c.cat_id = b.cat_id
    ORDER BY c.cat_id, b.book_id;
";

$result = $conn->query($query);
$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[$row['cat_id']]['name'] = $row['cat_name'];
    $categories[$row['cat_id']]['books'][] = [
        'book_id' => $row['book_id'],
        'book_name' => $row['book_name']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Shelf</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .shelf {
            background: url('https://via.placeholder.com/1500x200.png?text=Wooden+Shelf+Background') no-repeat center center;
            background-size: cover;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .shelf-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: #6c757d;
        }

        .books-container {
            display: flex;
            flex-wrap: nowrap; /* Prevent wrapping to a new line */
            overflow-x: auto; /* Enable horizontal scrolling */
            padding: 10px 0;
        }

        .book {
            margin: 0 10px; /* Space between books */
            flex: 0 0 100px; /* Fixed width for each book */
            transition: transform 0.3s;
            position: relative; /* Allow absolute positioning of text */
            text-align: center; /* Center text */
        }

        .book img {
            max-width: 100%; /* Ensure the image fits within the book */
            border-radius: 5px;
        }

        .book-name {
            position: absolute; /* Positioning */
            bottom: 0; /* Stick to the bottom */
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.8); /* Background for readability */
            padding: 5px 0; /* Padding for text */
            font-size: 0.9rem; /* Font size for book name */
            color: #333;
            overflow: hidden; /* Hide overflow */
            text-overflow: ellipsis; /* Show ellipsis for overflow */
            white-space: nowrap; /* Prevent wrapping */
        }

        .book:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow */
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Book Shelf</h2>
    
    <?php foreach ($categories as $category): ?>
        <div class="shelf">
            <div class="shelf-title"><?= htmlspecialchars($category['name']) ?></div>
            <div class="books-container">
                <?php 
                // Limit to 6 books displayed initially
                $booksToShow = array_slice($category['books'], 0, 6);
                foreach ($booksToShow as $book): 
                ?>
                    <div class="book" onclick="window.location.href='home.php'">
                        <img src="https://via.placeholder.com/80x120" alt="Book Image">
                        <div class="book-name"><?= htmlspecialchars($book['book_name']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>