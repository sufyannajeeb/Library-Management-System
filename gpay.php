<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: gpay.php");
    exit();
}

// Database connection
$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) 
    die("Connection failed: " . $connection->connect_error);

// Assuming the user ID is stored in the session
$user_id = $_SESSION['id'];
$request_id = $_SESSION['request_id'] ?? 0; // Fetch the relevant request ID from session or default to 0

// Fetch payment verification status
$sql = "SELECT payment_verified FROM book_requests WHERE user_id = ? AND request_id = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param('ii', $user_id, $request_id);
$stmt->execute();
$result = $stmt->get_result();

$payment_verified = false; // Default to false
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $payment_verified = (bool)$row['payment_verified']; // Convert to boolean
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Improved Payment Page</title>
    <style>
        /* Your existing CSS styles */
        body {
            font-family: 'Helvetica Neue', sans-serif;
            background-color: #f0f4f7;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .header {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            display: flex;
            width: 100%;
            height: calc(100% - 80px); /* Adjust height to fit header */
        }

        .left-section {
            width: 50%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .right-section {
            width: 50%;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Added for better layout */
        }

        .payment-container {
            width: 80%;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }

        h3 {
            color: #495057;
            margin-bottom: 15px;
            font-weight: normal;
            text-align: center;
        }

        p {
            color: #6c757d;
            margin-bottom: 20px;
            text-align: center;
        }

        .payment-options {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .payment-options img {
            width: 60px;
            height: auto;
            margin: 5px;
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .payment-options img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .payment-section {
            display: none;
            margin-top: 20px;
        }

        .qr-section img {
            display: block;
            margin: 20px auto;
            width: 200px;
            height: 200px;
        }

        .card {
            background: linear-gradient(135deg, #292E49, #536976);
            color: white;
            width: 350px;
            height: 220px;
            border-radius: 15px;
            padding: 20px;
            margin: 20px auto;
            position: relative;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .chip {
            width: 50px;
            height: 30px;
            background-color: white; /* Chip background color */
            position: absolute;
            top: 20px;
            right: 20px; /* Move chip to the right side */
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            overflow: hidden; /* Ensures the image fits within the chip */
        }

        .chip img {
            width: 100%; /* Set the image width to 100% of the chip */
            height: 100%; /* Set the image height to 100% of the chip */
            object-fit: cover; /* Ensures the image covers the entire chip without distortion */
            display: block; /* Ensure it's a block element */
        }

        .bank-name {
            text-transform: uppercase;
        }

        .card-number {
            margin-top: 60px;
            font-size: 18px;
            letter-spacing: 2px;
        }

        .card-holder {
            margin-top: 20px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .confirm-btn {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 20px;
        }

        .confirm-btn:hover {
            background-color: #218838;
        }

        .failure-message {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-top: 20px;
        }

        /* General input styles for card details */
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Back Button */
        #back-button {
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0; /* Added margin for better spacing */
            font-size: 16px;
        }

        #back-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Library Management System</h1>
        <p>Pay your fines securely using Google Pay or your ATM card.</p>
    </div>

    <div class="container">
        <div class="left-section">
            <div class="payment-container">
                <h1>Secure Payment</h1>

                <!-- Google Pay Button -->
                <div class="payment-options">
                    <img src="images/download.jpeg" alt="Google Pay" title="Pay with Google Pay" onclick="showQRSection()">
                </div>

                <!-- QR Code Section -->
                <div class="payment-section qr-section" id="qr-section">
                    <h3>Scan the QR for your payment</h3>
                    <img src="images/qr.jpg" alt="QR Code">
                    <p>Pay ₹5 fine for Library Management System</p>
                </div>
            </div>
        </div>

        <!-- ATM Card Section -->
        <div class="right-section">
            <div class="card">
                <div class="chip">
                    <img src="images/Payment/OIP.jpeg" alt="SIM Chip">
                </div>
                <div class="bank-name">Bank Name</div>
                <div class="card-number">**** **** **** 3456</div>
                <div class="card-holder"><?php echo htmlspecialchars($_SESSION['name']); ?></div>
            </div>

            <!-- Payment Failure Message -->
            <div class="failure-message" id="failure-message" style="display: none;">
                Payment failed unfortunately
            </div>

            <!-- Back Button -->
            <button id="back-button" onclick="goBack()">
                Back
            </button>
        </div>
    </div>

    <script>
        function showQRSection() {
            document.getElementById('qr-section').style.display = 'block';
        }

        function goBack() {
            window.history.back(); // Redirect to the previous page in the browser history
        }
    </script>
</body>
</html>
