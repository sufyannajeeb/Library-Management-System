<?php
// Database connection
$host = 'localhost';
$db = 'lms';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Adjust the SQL query to join with authors table
$query = "
    SELECT c.cat_id, c.cat_name, b.book_id, b.book_name, a.author_name, b.book_no, b.book_price
    FROM category c
    LEFT JOIN books b ON c.cat_id = b.cat_id
    LEFT JOIN authors a ON b.author_id = a.author_id
    ORDER BY c.cat_id, b.book_id;
";

$result = $conn->query($query);
$categories = [];

if ($result->num_rows > 0) {
    // Populate the categories array
    while ($row = $result->fetch_assoc()) {
        if (!isset($categories[$row['cat_id']])) {
            $categories[$row['cat_id']] = [
                'name' => $row['cat_name'],
                'books' => []
            ];
        }
        // Only include books that are not null
        if ($row['book_id'] !== null) {
            $categories[$row['cat_id']]['books'][] = [
                'book_id' => $row['book_id'],
                'book_name' => $row['book_name'],
                'author_name' => $row['author_name'], // Store the author name
                'book_no' => $row['book_no'],
                'book_price' => $row['book_price'],
            ];
        }
    }
} else {
    echo "No books found!";
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
            background: url('images/book shelf/empty-room-gray-wall-room-with-wooden-floor_53876-128781.avif') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1rem;
            z-index: 10;
        }
        .shelf {
            position: relative;
            background: url('images/book shelf/realistic-bookshelf-mockup-template-vintage-vector-39064396.jpg') no-repeat center center;
            background-size: cover;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: row;
        }
        .shelf-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
            color: white;
            flex: 1;
        }
        .books-container {
            display: flex;
            overflow-x: auto;
            padding: 10px 0;
            flex: 3;
            max-width: 100%;
        }
        .book {
            flex: 0 0 8%;
            margin: 0 5px;
            height: 160px;
            background-color: transparent;
            color: black;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            text-align: center;
            font-size: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition added */
        }
        .book:hover {
            transform: scale(1.2);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4); /* Add smoother hover effect */
        }
        .book img {
            max-width: 60%;
            max-height: 80%;
            border-radius: 4px;
            display: block;
            margin: 0 auto;
        }
        .book-name {
            padding: 5px;
            width: 100%;
            color: white;
            font-size: 0.75rem;
        }
        .arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .arrow-left {
            left: 0;
        }
        .arrow-right {
            right: 0;
        }
        .book-details {
            position: fixed;
            top: 100px;
            left: 20px;
            width: 255px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        .book-details h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .book-details p {
            margin: 5px 0;
        }
        .category-title {
            text-align: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: black;
        }
        .jump-button {
            position: fixed;
            right: 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10;
        }
        .jump-top {
            top: 20px;
        }
        .jump-bottom {
            bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <button class="back-button" onclick="window.history.back()">Back</button>
    <h2 class="text-center my-4">Book Shelf</h2>

    <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat_id => $category): ?>
            <?php if (!empty($category['books'])): ?>
                <h3 class="category-title"><?= htmlspecialchars($category['name']) ?></h3>
                <div class="shelf">
                    <button class="arrow arrow-left" onclick="scrollBooks('<?= $cat_id ?>', -1)">&#10094;</button>
                    <div class="books-container" id="books-<?= $cat_id ?>">
                        <?php foreach ($category['books'] as $book): ?>
                            <div class="book"
                                 onmouseover="showBookDetails('<?= htmlspecialchars($book['book_name']) ?>', '<?= htmlspecialchars($book['author_name']) ?>', '<?= htmlspecialchars($book['book_no']) ?>', '<?= htmlspecialchars($book['book_price']) ?>')"
                                 onmouseenter="scaleBook(this)" onmouseleave="resetBook(this)">
                                <img src="images/book shelf/IMG_0121.PNG" alt="Book">
                                <div class="book-name"><?= htmlspecialchars(substr($book['book_name'], 0, 20)) . (strlen($book['book_name']) > 20 ? '...' : '') ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="arrow arrow-right" onclick="scrollBooks('<?= $cat_id ?>', 1)">&#10095;</button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No books found!</p>
    <?php endif; ?>
</div>

<div class="book-details" id="book-details">
    <h3>Book Details</h3>
    <p id="book-name">Book Name:</p>
    <p id="author-name">Author Name:</p>
    <p id="book-no">Book No:</p>
    <p id="book-price">Price:</p>
</div>

<button class="jump-button jump-top" id="jumpTop" onclick="jumpTo('top')">&#8593;</button>
<button class="jump-button jump-bottom" id="jumpBottom" onclick="jumpTo('bottom')">&#8595;</button>

<script>
    function showBookDetails(name, author, bookNo, price) {
        document.getElementById('book-name').textContent = 'Book Name: ' + name;
        document.getElementById('author-name').textContent = 'Author Name: ' + author;
        document.getElementById('book-no').textContent = 'Book No: ' + bookNo;
        document.getElementById('book-price').textContent = 'Price: ' + price;
        document.getElementById('book-details').style.display = 'block';
    }

    function scaleBook(element) {
        element.style.transform = 'scale(1.1)';
    }

    function resetBook(element) {
        element.style.transform = 'scale(1)';
    }

    function scrollBooks(catId, direction) {
        const container = document.getElementById('books-' + catId);
        const scrollAmount = direction * 150; // Adjust this value to control scroll speed
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }

    function jumpTo(position) {
        if (position === 'top') {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }

    window.onscroll = function () {
        const jumpTopButton = document.getElementById('jumpTop');
        const jumpBottomButton = document.getElementById('jumpBottom');

        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            jumpTopButton.style.display = 'block';
        } else {
            jumpTopButton.style.display = 'none';
        }

        if (window.innerHeight + window.scrollY >= document.body.offsetHeight) {
            jumpBottomButton.style.display = 'none';
        } else {
            jumpBottomButton.style.display = 'block';
        }
    };
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
