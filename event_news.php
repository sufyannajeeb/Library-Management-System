<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events & News</title>
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <style>
        /* Sidebar Styles */
        #side_bar {
            background-color: #f8f9fa; /* Light background color */
            border-radius: 8px; /* Rounded corners */
            padding: 20px; /* Padding around the content */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow for depth */
            animation: slideIn 1s ease-out;
            margin-top: 20px; /* Space from the top */
        }

        /* Sidebar heading styles */
        #side_bar h5 {
            font-size: 1.25rem;
            color: #007bff; /* Primary color */
            margin-bottom: 15px; /* Space below headings */
            position: relative;
            font-weight: bold;
        }

        /* Heading for Events & News */
        #events-news-heading {
            font-size: 2rem;
            color: #343a40; /* Dark color for better visibility */
            text-align: center;
            margin-bottom: 20px; /* Space below the heading */
            position: relative;
            padding-bottom: 10px;
        }

        #events-news-heading::before {
            content: "";
            display: block;
            width: 60px; /* Width of the underline */
            height: 4px; /* Height of the underline */
            background-color: #007bff; /* Underline color */
            margin: 0 auto; /* Center the underline */
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            transform: scaleX(0);
            transform-origin: bottom left;
            transition: transform 0.3s ease;
        }

        #events-news-heading:hover::before {
            transform: scaleX(1);
        }

        /* Sidebar list styles */
        #side_bar ul {
            list-style: none; /* Remove default list styling */
            padding: 0;
        }

        #side_bar ul li {
            font-size: 1rem;
            color: #333; /* Dark text color for readability */
            margin-bottom: 10px; /* Space between list items */
        }

        /* Hover animation for list items */
        #side_bar ul li:hover {
            color: #007bff; /* Change text color on hover */
            transform: translateX(5px); /* Slight movement effect */
            transition: color 0.3s, transform 0.3s; /* Smooth transitions */
        }

        /* Keyframes for slide-in animation */
        @keyframes slideIn {
            from {
                transform: translateX(-30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Button Styles */
        .btn-back {
            display: block;
            width: 200px;
            margin: 20px auto; /* Center align */
            padding: 10px 15px;
            font-size: 1rem;
            color: #fff;
            background-color: #007bff; /* Primary color */
            border: none;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-back:hover {
            background-color: #0056b3; /* Darker shade on hover */
            transform: scale(1.05); /* Slightly enlarge */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 id="events-news-heading">Events & News</h1>
                <a href="index.php" class="btn-back">Back to Home</a> <!-- Button added here -->
            </div>
            <div class="col-md-4" id="side_bar">
                <div class="sidebar-content">
                    <h5>Library Timing</h5>
                    <ul>
                        <li>Opening: 8:00 AM</li>
                        <li>Closing: 8:00 PM</li>
                        <li>(Sunday Off)</li>
                    </ul>
                    <h5>What We Provide?</h5>
                    <ul>
                        <li>Full Furniture</li>
                        <li>Free Wi-Fi</li>
                        <li>Newspapers</li>
                        <li>Discussion Room</li>
                        <li>RO Water</li>
                        <li>Peaceful Environment</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
