<?php
require("functions.php");
ob_start();
session_start();

// Check if admin is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch categories
$query = "SELECT * FROM category";
$query_run = mysqli_query($connection, $query);
if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book's Category</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <style>
        /* Body Style */
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }

        /* Navbar Custom */
        .navbar-custom {
            background-color: #343a40;
        }

        /* Container Style */
        .container {
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Category Dropdown */
        #categorySelect {
            font-size: 1.1em;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #343a40;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        #categorySelect:hover {
            border-color: #6c757d;
        }

        /* Table Custom */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            animation: slideIn 0.5s ease-in-out;
        }

        .table-custom th, .table-custom td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.3s ease;
        }

        .table-custom th {
            background-color: #343a40;
            color: white;
            text-align: center;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#categorySelect').on('change', function() {
                var categoryId = $(this).val();
                if (categoryId) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_books.php',
                        data: 'cat_id=' + categoryId,
                        success: function(html) {
                            $('#bookList').html(html);
                        }
                    });
                } else {
                    $('#bookList').html('<tr><td colspan="2">Please select a category.</td></tr>');
                }
            });
        });
    </script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
        </div>
        <font style="color: white">
            <span><strong>Welcome: (admin) <?php echo htmlspecialchars($_SESSION['name']); ?></strong></span>
        </font>
        <font style="color: white">
            <span><strong>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></strong></span>
        </font>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="view_profile.php">View Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="change_password.php">Change Password</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <center><h4>Select a Category to View Books</h4><br></center>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <select id="categorySelect" class="form-control">
                <option value="">Select Category</option>
                <?php
                while ($row = mysqli_fetch_assoc($query_run)) {
                    echo "<option value='" . htmlspecialchars($row['cat_id']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                }
                ?>
            </select>
            <br>
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Author Name</th>
                    </tr>
                </thead>
                <tbody id="bookList">
                    <tr><td colspan="2">Please select a category.</td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>
</body>
</html>

<?php
mysqli_close($connection);
?>
