<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password ?</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
            text-align: left;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background: #0056b3;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Forgot Password ?</h2>
        <br>
        <form action="process_forgot_password.php" method="post">
            <label for="email">Enter your email address:</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Reset Password</button>
        </form>
        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>
</html>
