<!DOCTYPE html>
<html>
<head>
    <title>USER REGISTRATION</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome CDN -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-image: url(images/clean-empty-library-hall.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .navbar {
            margin-bottom: 20px;
            background-color: transparent !important;
        }

        .navbar-nav .nav-link {
            color: whitesmoke !important;
        }

        .form-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex: 4;
            opacity: 0.66;
            padding: 20px;
        }

        .form-content {
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            animation: slide-in 0.5s ease-out;
            margin-right: 20px;
            background-color: transparent;
        }

        .form-group {
            color: white;
        }

        @keyframes slide-in {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .password-strength {
            height: 10px;
            margin-top: 5px;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease;
        }

        .password-strength-bar.green {
            background-color: #28a745;
        }

        .password-strength-bar.red {
            background-color: #dc3545;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #343a40;
        }

        button {
            background-color: #6A0DAD;
            border: none;
        }

        button:hover {
            background-color: black;
        }

        footer {
            background-color: transparent;
            color: #000;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
            margin-top: auto;
        }

        .navbar-brand {
            color: white;
            font-size: 24px;
        }

        .navbar-brand:hover {
            background-color: transparent;
            color: black;
        }

        .navbar-brand .fa-home {
            font-size: 24px;
        }

        .animated-text {
            position: fixed;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 600px;
            padding: 40px;
            color: #ffdd57;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            animation: slideIn 1s ease-out;
            animation-fill-mode: forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50%) translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(-50%) translateX(0);
                opacity: 1;
            }
        }

        .animated-text:hover {
            animation: bounce 1s ease;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        /* Hide all form sections by default */
        .form-step {
            display: none;
        }

        /* Show the first step by default */
        .form-step.active {
            display: block;
        }

        .password-rules {
            color: #fff;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-home"></i> <!-- Font Awesome home icon -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="admin/index.php">Admin Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="form-container">
    <div class="form-content">
        <h3><u style="color: white;">Registration Form</u></h3>
        <form action="register.php" method="post" id="registrationForm" onsubmit="return validateForm()">
            <!-- Step 1: Name and Email -->
            <div class="form-step active" id="step1">
                <div class="form-group">
                    <label for="name">Full Name:</label>
                    <input type="text" name="name" id="name" class="form-control" required oninput="validateNameAndEmail()">
                </div>
                <div class="form-group">
                    <label for="email">Email ID:</label>
                    <input type="email" name="email" id="email" class="form-control" required oninput="validateNameAndEmail()">
                </div>
                <button type="button" class="btn btn-primary btn-block" id="nextBtn1" onclick="nextStep(2)" disabled>Next</button>
            </div>

            <!-- Step 2: Password and Confirm Password -->
            <div class="form-step" id="step2">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required oninput="updatePasswordStrength()">
                    <div class="password-rules">
                        <p>(Password: must be at least 8 characters long, contain an uppercase letter, a number, and a special character) (@#$%^&*!).</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="re_password">Re-enter Password:</label>
                    <input type="password" name="re_password" id="re_password" class="form-control" required oninput="validatePasswords()">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-block" onclick="prevStep(1)">Back</button>
                <button type="button" class="btn btn-primary btn-block" id="nextBtn2" onclick="nextStep(3)" disabled>Next</button>
            </div>

            <!-- Step 3: Mobile and Address -->
            <div class="form-step" id="step3">
                <div class="form-group">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
                </div>
                <button type="button" class="btn btn-secondary btn-block" onclick="prevStep(2)">Back</button>
                <button type="submit" class="btn btn-success btn-block">Register</button>
            </div>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Your Library Management System</p>
</footer>

<script>
    function validateNameAndEmail() {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const nextBtn1 = document.getElementById('nextBtn1');
        
        // Enable the button if both fields are filled
        nextBtn1.disabled = !(name && email);
    }

    function updatePasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('passwordStrengthBar');
        
        let strength = 0;

        // Check password length
        if (password.length >= 8) strength++;
        // Check for uppercase letters
        if (/[A-Z]/.test(password)) strength++;
        // Check for numbers
        if (/\d/.test(password)) strength++;
        // Check for special characters
        if (/[!@#$%^&*]/.test(password)) strength++;

        switch (strength) {
            case 0:
                strengthBar.style.width = '0%';
                strengthBar.className = 'password-strength-bar';
                break;
            case 1:
                strengthBar.style.width = '25%';
                strengthBar.className = 'password-strength-bar red';
                break;
            case 2:
                strengthBar.style.width = '50%';
                strengthBar.className = 'password-strength-bar red';
                break;
            case 3:
                strengthBar.style.width = '75%';
                strengthBar.className = 'password-strength-bar green';
                break;
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.className = 'password-strength-bar green';
                break;
        }

        // Validate passwords
        validatePasswords();
    }

    function validatePasswords() {
        const password = document.getElementById('password').value;
        const rePassword = document.getElementById('re_password').value;
        const nextBtn2 = document.getElementById('nextBtn2');

        // Check if passwords match
        if (password && rePassword) {
            nextBtn2.disabled = password !== rePassword || passwordStrength(password) < 4; // Ensure password strength is sufficient
        } else {
            nextBtn2.disabled = true; // Disable if either field is empty
        }
    }

    function passwordStrength(password) {
        let strength = 0;

        // Check password length
        if (password.length >= 8) strength++;
        // Check for uppercase letters
        if (/[A-Z]/.test(password)) strength++;
        // Check for numbers
        if (/\d/.test(password)) strength++;
        // Check for special characters
        if (/[!@#$%^&*]/.test(password)) strength++;

        return strength;
    }

    function nextStep(step) {
        const steps = document.querySelectorAll('.form-step');
        steps.forEach((s) => s.classList.remove('active'));
        document.getElementById(`step${step}`).classList.add('active');
    }

    function prevStep(step) {
        nextStep(step);
    }

    function validateForm() {
        // You can perform final validation here if needed before submitting
        return true; // Allow form to submit
    }
</script>
</body>
</html>
