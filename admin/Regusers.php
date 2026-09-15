<?php
ob_start();
session_start();

// Fetch data from the database
$connection = mysqli_connect("localhost", "root", "", "lms");
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize the search query
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($connection, $_GET['search']);
}

$query = "SELECT * FROM users WHERE 
          name LIKE '%$search_query%' OR 
          email LIKE '%$search_query%' OR 
          mobile LIKE '%$search_query%' OR 
          address LIKE '%$search_query%'";
?>
<!DOCTYPE html>
<html>
<head>
    <title>Regular Users</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>

    <style>
        /* Body and Background */
        body {
            background-image: url('../images/6.jpg');
            color: #e0e0e0;
            font-family: 'Roboto', sans-serif;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #1f1f1f;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: bold;
            font-size: 1.5em;
            color: #ffffff;
        }

        .navbar-custom .nav-link {
            color: #cfcfcf;
        }

        /* Marquee Container */
        .marquee-container {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
            background: linear-gradient(90deg, #6A0DAD, #8E44AD);
            color: white;
            padding: 10px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        /* Table Styling */
        .table-custom {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .table-custom th, .table-custom td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #333;
            color: white;
        }

        .table-custom th {
            background-color: #2a2a2a;
            font-size: 1.1em;
        }

        .table-custom tbody tr:hover {
            background-color: #333;
        }

        .table-custom td {
            transition: background-color 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-custom {
                font-size: 0.9em;
            }
        }

        /* Search Bar Styling */
        .search-bar {
            width: 100%;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 16px;
        }
    </style>
    
    <script>
        $(document).ready(function() {
            // When the search input changes
            $('.search-input').on('input', function() {
                var searchQuery = $(this).val(); // Get the value of the search input
                $.ajax({
                    url: 'search_users.php', // File to process the search
                    method: 'POST', // Method to use for the request
                    data: { query: searchQuery }, // Data to send (search query)
                    success: function(response) {
                        $('tbody').html(response); // Update the table body with the search results
                    }
                });
            });
        });
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin_dashboard.php">Library Management System (LMS)</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
            </ul>
            <span class="navbar-text">
                Welcome: (admin) <?php echo htmlspecialchars($_SESSION['name']); ?> |
                Email: <?php echo htmlspecialchars($_SESSION['email']); ?>
            </span>
        </div>
    </div>
</nav>

<br><br>

<div class="container" style="color: white;">
    <center><h4>Registered Users Detail</h4></center>

    <!-- Search Bar -->
    <div class="row search-bar">
        <div class="col-md-12">
            <input type="text" name="search" class="search-input" placeholder="Search users..." value="<?php echo htmlspecialchars($search_query); ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_run = mysqli_query($connection, $query);
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $name = htmlspecialchars($row['name']);
                            $email = htmlspecialchars($row['email']);
                            $mobile = htmlspecialchars($row['mobile']);
                            $address = htmlspecialchars($row['address']);
                    ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $mobile; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $address; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="4">No users found.</td></tr>';
                    }
                    ?>
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
