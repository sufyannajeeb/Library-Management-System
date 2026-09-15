<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Chat</title>
    <style>
        /* Basic styling */
        #messageList { margin-top: 20px; }
        .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .user { background-color: #e0f7fa; }
        .admin { background-color: #e8f5e9; }
    </style>
</head>
<body>
    <h1>User Chat</h1>
    <textarea id="message" placeholder="Type your message..."></textarea><br>
    <button onclick="sendMessage()">Send Message</button>

    <div id="messageList">
        <!-- Messages will appear here -->
    </div>

    <script>
        // Function to send the message (user sends message to admin)
        function sendMessage() {
            var message = document.getElementById("message").value;
            if (message.trim() === "") return;

            // Send the message to the backend (backend_user.php)
            fetch('backend_user.php', {
                method: 'POST',
                body: JSON.stringify({ message: message }), // Sending message only
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById("messageList").innerHTML += <div class="message user">${message}</div>;
                document.getElementById("message").value = '';  // Clear the input
                console.log(data); // For debugging purposes
            });
        }
    </script>
</body>
</html>


ui_user.php