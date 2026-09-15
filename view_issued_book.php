<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$connection = new mysqli("localhost", "root", "", "lms");
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$user_email = $_SESSION['email'];

$query = "SELECT br.request_id, b.book_name, br.request_date, br.status, br.book_collected,
          DATE_ADD(br.request_date, INTERVAL 3 MINUTE) AS return_date,
          DATE_ADD(DATE_ADD(br.request_date, INTERVAL 3 MINUTE), INTERVAL 2 MINUTE) AS due_date,
          br.extend_requested
          FROM book_requests br 
          JOIN books b ON br.book_id = b.book_id 
          JOIN users u ON br.user_id = u.id
          WHERE u.email = '$user_email'";

$query_run = $connection->query($query);
if (!$query_run) {
    die("Query failed: " . $connection->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issued Book Status</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../bootstrap-4.4.1/css/bootstrap.min.css">
    <link rel="icon" sizes="16x16" href="images/l.jpg">
    <script type="text/javascript" src="../bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="../bootstrap-4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
      /* Your existing styles */
      /* Ensure styles are clear and consistent */
      body {
        background-color: #f0f0f0;
        font-family: 'Roboto', sans-serif;
        margin: 0;
      }

      .navbar-custom {
        background-color: #000000;
        color: #ffd700;
        padding: 0.5rem 1rem;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        text-align: left;
      }

      .navbar-custom .navbar-brand {
        color: #ffd700;
        font-size: 1.8rem;
        font-weight: 700;
        margin-right: 1rem;
      }

      .navbar-custom .navbar-nav {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
        display: flex;
        align-items: center;
      }

      .navbar-custom .navbar-nav .nav-item {
        margin: 0;
      }

      .navbar-custom .navbar-nav .nav-link {
        color: #ffd700;
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        border: 2px solid transparent;
        border-radius: 4px;
        transition: background-color 0.3s, border-color 0.3s;
        text-decoration: none;
      }

      .navbar-custom .navbar-nav .nav-link:hover {
        color: #000000;
        background-color: #ffd700;
        border-color: #ffd700;
      }

      .navbar-custom .navbar-nav .nav-link.back-button {
        margin-left: 0;
        margin-right: 1rem;
      }

      .content {
        padding-top: 80px;
      }

      .container {
        max-width: 1200px;
        margin: auto;
      }

      h4 {
        margin-bottom: 20px;
        text-align: center;
        color: #343a40;
        font-weight: 700;
      }

      .table-custom {
        background-color: #ffffff;
        width: 100%;
        text-align: center;
        border-radius: 5px;
        overflow: hidden;
      }

      .table-custom thead th {
        background-color: #343a40;
        color: #ffffff;
        font-weight: bold;
      }

      .table-custom tbody tr {
        transition: background-color 0.3s ease;
      }

      .table-custom tbody tr:hover {
        background-color: #f8f9fa;
      }

      .table-custom th, .table-custom td {
        border: 1px solid #dee2e6;
        padding: 10px;
      }

      .text-success {
        color: #28a745;
      }

      .text-danger {
        color: #dc3545;
      }

      .btn-warning {
        background-color: #ff9800;
        color: #ffffff;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        transition: background-color 0.3s ease, transform 0.3s ease;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      .btn-warning:hover {
        background-color: #e68900;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
      }

      .btn-warning:active {
        background-color: #cc7a00;
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      }

      .extend-form {
        display: none;
      }
    </style>
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="user_dashboard.php">Library System</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link back-button" href="user_dashboard.php">Back</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<div class="content">
    <div class="container">
        <h4>Issued Book Status</h4>
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Book Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Return Date</th>
                    <th>Collection Status</th>
                    <th>Extend Book</th>
                    <th>Extension Status</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($row = $query_run->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['request_id'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['book_name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['request_date'] ?? 'N/A'); ?></td>
            <td class="<?php echo $row['status'] === 'approved' ? 'text-success' : ($row['status'] === 'rejected' ? 'text-danger' : ''); ?>">
                <?php echo htmlspecialchars(ucfirst($row['status'] ?? 'Unknown')); ?>
            </td>
            <td>
                <?php if ($row['return_date']): ?>
                    <?php if (strtotime($row['return_date']) < time()): ?>
                        <span class="text-danger">Overdue</span>
                    <?php else: ?>
                        <?php echo htmlspecialchars($row['return_date']); ?>
                    <?php endif; ?>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <?php echo $row['book_collected'] ? 'Collected' : 'Not Collected'; ?>
            </td>
            <td>
                <?php 
                if ($row['book_collected']): ?>
                    <button type="button" class="btn btn-warning extend-btn" data-request-id="<?php echo htmlspecialchars($row['request_id']); ?>">
                        Extend Return Date
                    </button>

                    <div class="extend-form" id="form-<?php echo htmlspecialchars($row['request_id']); ?>" style="display:none;">
                        <form action="process_extension_request.php" method="POST">
                            <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['request_id']); ?>">
                            <div class="form-group">
                                <label for="new_return_date">New Return Date (Max 7 days):</label>
                                <input type="date" class="form-control" name="new_return_date" max="<?php echo date('Y-m-d', strtotime('+7 days')); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="extension_reason">Extension Reason:</label>
                                <textarea class="form-control" name="extension_reason" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning">Submit Extension Request</button>
                        </form>
                    </div>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
            <td>
                <?php echo $row['extend_requested'] ? 'Requested' : 'Not Requested'; ?>
            </td>
        </tr>
    <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Show the form when the "Extend Return Date" button is clicked
    $('.extend-btn').on('click', function() {
        var requestId = $(this).data('request-id');
        $('#form-' + requestId).toggle();
    });

    // Handle form submission via AJAX
    $('.extension-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var form = $(this);
        var requestId = form.find('input[name="request_id"]').val();
        var newReturnDate = form.find('input[name="new_return_date"]').val();
        var extensionReason = form.find('textarea[name="extension_reason"]').val();

        $.ajax({
            url: 'process_extension_request.php',
            type: 'POST',
            data: {
                request_id: requestId,
                new_return_date: newReturnDate,
                extension_reason: extensionReason
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.message === 'Extension request submitted successfully') {
                    alert('Extension request submitted successfully!');
                    location.reload(); // Reload the page to reflect the changes
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('An error occurred while submitting the extension request.');
            }
        });
    });
});
</script>



</body>
</html>
