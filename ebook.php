<?php
// Database connection
$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "lms"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch e-books using prepared statements
$sql = "SELECT * FROM ebooks";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Book Collection</title>
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #121212;
            color: #f0e68c;
        }
        .container {
            flex: 1;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .ebook-card {
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2e2e2e, #1f1f1f);
            border: 1px solid #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeIn 0.5s ease-in;
        }
        .ebook-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
        }
        .footer {
            background-color: #333;
            color: #f0e68c;
            padding: 20px;
            border-top: 1px solid #444;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
        }
        .footer a {
            color: #f0e68c;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
            color: #ffd700;
        }
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
            animation: slideIn 0.5s ease-in;
            background-color: #2e2e2e;
            color: #f0e68c;
        }
        .modal-body {
            padding: 2rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-text {
            animation: textFadeIn 1s ease-in-out;
        }
        @keyframes textFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-button {
            animation: buttonFadeIn 1s ease-in-out;
        }
        @keyframes buttonFadeIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .ebook-card img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .back {
        display: inline-block;
        padding: 8px 16px;
        font-size: 1rem;
        font-weight: 800;
        color: #ffffff;
        background-color: #007bff; /* Blue background */
        border: none;
        border-radius: 4px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-top: 25px;
    
    }

    .back:hover {
    background-color: #0056b3; /* Darker blue on hover */
    transform: translateY(-2px);
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
}

.back:active {
    background-color: #004085; /* Even darker blue on click */
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #333;">
        <div class="container">
            <a class="navbar-brand" href="home.php">Library Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ebook.php">E-Books</a>
                    </li>
                    <!-- Add more links as needed -->
                </ul>
                <a href="#" class="back" onclick="goBack()">Back</a>

    </nav>
   
    <div class="container">
        <h1 class="my-4 text-center text-warning animate-text">E-Book Collection</h1>
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="ebook-card">
                            <img src="<?php echo htmlspecialchars($row['thumbnail_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <h4 class="text-warning"><?php echo htmlspecialchars($row['title']); ?></h4>
                            <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($row['file_url']); ?>" class="btn btn-warning animate-button" download>Download</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">No e-books available.</p>
            <?php endif; ?>
        </div>

        <!-- Back to Home Button -->
        <div class="text-center my-4">
            <a href="home.php" class="btn btn-warning animate-button">Back to Home</a>
        </div>
    </div>

    <!-- Contribution Modal -->
    <div class="modal fade" id="contributeModal" tabindex="-1" aria-labelledby="contributeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contributeModalLabel">Contribute an E-book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="contribute.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Library Management System. All rights reserved.</p>
            <p>
                <a href="#">Privacy Policy</a> | 
                <a href="#">Terms of Service</a> | 
                <a href="#">Contact Us</a>
            </p>
            <p>
                <a href="#" data-bs-toggle="modal" data-bs-target="#contributeModal">Contribute an E-book</a>
            </p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    
    <script>
        function goBack() {
    window.history.back();
}

        </script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
