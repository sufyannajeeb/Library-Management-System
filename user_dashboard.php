<?php
ob_start();
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

session_regenerate_id(true); // Call this after a successful login

// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Include the functions file
require_once 'functions.php';

$user_id = $_SESSION['id'];

// Fetch book request with overdue payment
$query = "SELECT payment_verified FROM book_requests WHERE user_id = ? AND return_status = 'Overdue' LIMIT 1";
$stmt = $connection->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && $row['payment_verified'] == 0) {
    echo '<div class="alert alert-danger">You have an overdue book. Please complete the payment to continue using the library services.</div>';
    $can_request = false;
    echo '<div class="text-center"><a href="gpay.php" class="btn btn-custom">Complete Payment</a></div>';
} else {
    $can_request = true;
}

// Fetch the count of unread notifications
$count_query = "SELECT COUNT(*) as count FROM user_notifications WHERE user_id = ? AND is_read = 0";
$count_stmt = $connection->prepare($count_query);
$count_stmt->bind_param("i", $user_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$notification_count = $count_result->fetch_assoc()['count'];

// Fetch notifications for the user
$notifications_query = "SELECT id, message, created_at FROM user_notifications WHERE user_id = ? ORDER BY created_at DESC";
$notifications_stmt = $connection->prepare($notifications_query);
$notifications_stmt->bind_param("i", $user_id);
$notifications_stmt->execute();
$notifications_result = $notifications_stmt->get_result();

// Store notifications in an array
$notifications = [];
while ($notification = $notifications_result->fetch_assoc()) {
    $notifications[] = $notification; // Store each notification
}

// Check if "Pay Now" button should be shown
$show_pay_now_button = false;
foreach ($notifications as $notification) {
    if (strpos($notification['message'], 'overdue book requests') !== false) {
        $show_pay_now_button = true; // Set flag if overdue notification is found
        break; // Exit loop early if found
    }
}

// Handle clear all notifications request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_notifications'])) {
    $clear_query = "DELETE FROM user_notifications WHERE user_id = ?";
    $clear_stmt = $connection->prepare($clear_query);
    $clear_stmt->bind_param("i", $user_id);
    $clear_stmt->execute();
    
    // Redirect to avoid re-sending the POST request
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle mark all as read request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    $update_query = "UPDATE user_notifications SET is_read = 1 WHERE user_id = ?";
    $update_stmt = $connection->prepare($update_query);
    $update_stmt->bind_param("i", $user_id);
    $update_stmt->execute();

    if ($update_stmt->affected_rows > 0) {
        // Redirect with a success message if needed
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=marked");
        exit();
    } else {
        echo '<div class="alert alert-warning">No unread notifications to mark.</div>';
    }
}

// Additional code for displaying success messages if needed...

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        /* Your existing CSS here */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .top-half {
            position: relative;
            padding: 40px 20px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 40vh;
            overflow: hidden;
        }

        .top-half video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.5);
        }

        .bottom-half {
            flex: 1;
            background-color: white;
            padding: 40px 20px;
        }

        .navbar-custom {
            background-color: transparent;
            width: 100%;
            z-index: 100;
            position: absolute;
            top: 0;
            padding-right: 40px;
        }

        .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
            color: white;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-weight: bolder;
        }

        .navbar-custom .navbar-nav .nav-link:hover {
            color: white;
        }

        .welcome-section {
            color: whitesmoke;
            padding: 40px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
        }

        .welcome-section h3 {
            font-family: 'Roboto', sans-serif;
            font-size: 1.5rem;
            font-weight: 300;
            margin-top: 20px;
        }

        .welcome-section h4 {
            font-family: 'Cursive', sans-serif;
            font-size: 1.8rem;
            margin-top: 30px;
            font-weight: 600;
            color: black; 
            text-shadow: 1px 1px 6px rgba(0, 0, 0, 0.2);
        }

        .welcome-section h4 .infinity {
            display: inline-block;
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 0;
        }

        .card {
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            margin-top: 10px;
            background-color: #1f3b73;
            color: white;
            border-color: #1f3b73;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #162d5e;
            color: white;
            transform: scale(1.05);
        }

        footer {
            color: black;
            text-align: center;
            padding: 10px 0;
            background-color: #f0f0f0;
            width: 100%;
            flex-shrink: 0;
        }

        footer a {
            color: black;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
            margin: 0 10px;
        }

        footer a:hover {
            color: darkblue;
        }

        @media (max-width: 768px) {
            .top-half {
                height: 30vh;
            }

            .card-container {
                grid-template-columns: 1fr;
            }

            .navbar-custom .navbar-brand, .navbar-custom .navbar-nav .nav-link {
                font-size: 14px;
            }
        }

        #notification-icon {
            color: #fff;
            position: fixed;
            top: 13px; 
            right: 20px; 
            cursor: pointer;
            z-index: 1000; 
            font-size: 32px; 
        }

        #notification-count {
            position: absolute;
            top: -10px; 
            right: -10px; 
            background-color: red; 
            border-radius: 50%; 
            padding: 5px 8px; 
            font-size: 18px; 
        }
    </style>
</head>
<body>

<!-- Notification Icon -->
<div id="notification-icon">
    <i class="fas fa-bell" style="font-size: 24px;"></i>
    <span id="notification-count" class="badge bg-danger"><?php echo $notification_count; ?></span>
</div>

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Alert for overdue payments -->
                <?php if (isset($total_due_amount) && $total_due_amount > 0): ?>
                    <div class="alert alert-danger text-center">
                        You have overdue books. Please complete the payment of $<?php echo number_format($total_due_amount, 2); ?> to continue using the library services.
                        <div class="mt-2">
                            <a href="gpay.php" class="btn btn-custom">Complete Payment</a>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="notification-list">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="notification-item">
                            <p><?php echo htmlspecialchars($notification['message']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($show_pay_now_button): ?>
                    <div class="text-center mt-3">
                        <button id="payNowButton" class="btn btn-primary">Pay Now</button>
                    </div>
                <?php endif; ?>

                <!-- Hidden Image with Confirmation Text -->
                <div id="confirmationImage" class="text-center mt-3" style="display: none;">
                    <img src="images/qr.jpg" alt="Payment Confirmation" class="img-fluid" style="max-width: 200px;">
                    <p>You will be notified once payment is verified by the Librarian.</p>
                </div>
            </div>
            <div class="modal-footer">
                <form method="post" style="display:inline;">
                    <button type="submit" name="mark_all_read" class="btn btn-success">Mark All as Read</button>
                </form>
                <form method="post" style="display:inline;">
                    <button type="submit" name="clear_notifications" class="btn btn-danger">Clear All</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Show notification modal when the icon is clicked
    document.getElementById('notification-icon').addEventListener('click', function() {
        var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        notificationModal.show();
    });

    // Show confirmation image and hide the Pay Now button when clicked
    document.getElementById('payNowButton')?.addEventListener('click', function() {
        document.getElementById('confirmationImage').style.display = 'block';
        document.getElementById('payNowButton').style.display = 'none'; // Hide the Pay Now button
    });
</script>

<div class="top-half">
    <!-- Add the video element -->
    <video autoplay muted loop>
        <source src="videos/user_dash.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-user"></i> Welcome: <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </li>
                <li class="nav-item">
                    <span class="nav-link"><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($_SESSION['email']); ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-cog"></i> My Profile
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php" style="color: green; text-decoration: none;" onmouseover="this.style.color='red';" onmouseout="this.style.color='green';">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <div class="welcome-section">
        <h1>Welcome to Your Dashboard</h1>
        <h3>Borrow books and many more...</h3>
        <h4><span class="infinity">&infin;</span> Learn, Read, Repeat <span class="infinity">&infin;</span></h4>
    </div>
</div>


<div class="bottom-half container">
    <div class="card-container">
        <div class="card">
            <div class="card-header">Books Issued</div>
            <div class="card-body">
                <p class="card-text">Number of books issued: <?php echo get_user_issue_book_count($connection);?></p>
                <a href="view_issued_book.php" class="btn btn-custom">View Issued Books</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Books Available</div>
            <div class="card-body">
                <p class="card-text">Number of books available: <?php echo get_available_books($connection);?></p>
                <a href="view_available_books.php" class="btn btn-custom">View Available Books</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">E-Books</div>
            <div class="card-body">
                <p class="card-text">Explore our E-Books Collection</p>
                <a href="ebook.php" class="btn btn-custom">View E-Books</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Audio Books</div>
            <div class="card-body">
                <p class="card-text">Listen to our collection of Audiobooks.</p>
                <a href="audiobook.php" class="btn btn-custom">View Audiobooks</a>
            </div>
        </div>
    </div>
</div>

<!-- Chatbot Icon (Lower right corner) -->
<div id="chat-bot">
    <div id="chat-icon" style="position: fixed; bottom: 20px; right: 20px; cursor: pointer; background-color: #007bff; border-radius: 50%; width: 60px; height: 60px; display: flex; justify-content: center; align-items: center; color: white; font-size: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <i class="fas fa-comment-dots"></i> <!-- Font Awesome Chat Icon -->
        <div id="notification-badge" style="position: absolute; top: -10px; right: -10px; width: 15px; height: 15px; background: red; border-radius: 50%; display: block;"></div>
    </div>

    <!-- Chat Window -->
    <div id="chat-window" style="position: fixed; bottom: 80px; right: 20px; width: 300px; height: 400px; background: #fff; border: 1px solid #ccc; display: none; border-radius: 10px;">
        <div id="chat-header" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #ccc;">
            <div id="chat-title">Chatbot</div>
            <button id="toggle-chat" style="background: none; border: none; font-size: 14px; cursor: pointer;">Switch to Librarian</button>
            <button id="close-chat" style="background: none; border: none; font-size: 18px; cursor: pointer;">&times;</button>
        </div>
        <div id="output" style="height: 320px; overflow-y: auto; padding: 10px;"></div>
        <div id="input-area" style="display: flex; border-top: 1px solid #ccc;">
            <input id="user-input" type="text" placeholder="Type here..." style="width: 80%; padding: 10px; border: none; outline: none;">
            <button id="send-message" style="width: 20%; background-color: #007bff; color: white; border: none; cursor: pointer;">Send</button>
        </div>
    </div>
</div>

<script>
let isLibrarian = false; // Track the current chat mode

// Function to toggle between Chatbot and Librarian
document.getElementById('toggle-chat').addEventListener('click', function () {
    isLibrarian = !isLibrarian; // Toggle the mode
    const chatTitle = document.getElementById('chat-title');
    const toggleButton = document.getElementById('toggle-chat');

    if (isLibrarian) {
        chatTitle.textContent = 'Librarian';
        toggleButton.textContent = 'Switch to Chatbot';
        appendBotMessage('You are now chatting with the Librarian.');
    } else {
        chatTitle.textContent = 'Chatbot';
        toggleButton.textContent = 'Switch to Librarian';
        appendBotMessage('You are now chatting with the Chatbot.');
    }
});

// Function to toggle chat window visibility
document.getElementById('chat-icon').addEventListener('click', function() {
    const chatWindow = document.getElementById('chat-window');
    chatWindow.style.display = (chatWindow.style.display === 'none' || chatWindow.style.display === '') ? 'block' : 'none';
});

// Close chat window
document.getElementById('close-chat').addEventListener('click', function() {
    document.getElementById('chat-window').style.display = 'none';
});

// Function to send a message
document.getElementById('send-message').addEventListener('click', sendMessage);
document.getElementById('user-input').addEventListener('keypress', function (event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
});

// Append user message to chat window
function appendUserMessage(message) {
    const chatContent = document.getElementById('output');
    const userMessage = document.createElement('div');
    userMessage.style.backgroundColor = '#d1f1d1';
    userMessage.style.padding = '10px';
    userMessage.style.margin = '10px 0';
    userMessage.style.borderRadius = '10px';
    userMessage.textContent = message;
    chatContent.appendChild(userMessage);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Append bot or librarian message to chat window
function appendBotMessage(message) {
    const chatContent = document.getElementById('output');
    const botMessage = document.createElement('div');
    botMessage.style.backgroundColor = '#f1f1f1';
    botMessage.style.padding = '10px';
    botMessage.style.margin = '10px 0';
    botMessage.style.borderRadius = '10px';
    botMessage.textContent = message;
    chatContent.appendChild(botMessage);
    chatContent.scrollTop = chatContent.scrollHeight;
}

// Function to handle sending user message
function sendMessage() {
    const userInput = document.getElementById('user-input');
    const userMessage = userInput.value.trim();

    if (userMessage) {
        appendUserMessage(userMessage); // Display user message
        if (isLibrarian) {
            sendMessageToLibrarian(userMessage); // Send to Librarian
        } else {
            getBotResponse(userMessage); // Send to Chatbot
        }
        userInput.value = ''; // Clear input field
    }
}

// Function to send message to Librarian (admin interface)
function sendMessageToLibrarian(userMessage) {
    appendBotMessage('Your message has been forwarded to the Librarian.');
    
    // Here, you can send the message to the admin interface.
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'admin_receive_message.php', true); // PHP file where the admin can read the message
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            console.log('Message sent to admin successfully');
        } else {
            console.error('Error in sending message to admin');
        }
    };
    xhr.send('message=' + encodeURIComponent(userMessage)); // Send message to admin backend
}

// Function to get a response from the Chatbot
function getBotResponse(userMessage) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'chatbot_response.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            appendBotMessage(xhr.responseText);
        } else {
            appendBotMessage('Sorry, something went wrong.');
        }
    };
    xhr.send('message=' + encodeURIComponent(userMessage));
}
</script>






</script>

<footer>
    <p>&copy; 2024 Library Management System | <a href="contact.php">Contact Us</a> | <a href="about.php">About</a></p>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
// Close database connection
$connection->close();
?>