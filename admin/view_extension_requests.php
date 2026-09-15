<?php
include('../admin/db_connect.php');

$message = $_GET['message'] ?? '';

if (!empty($message)) {
    echo '<div class="alert alert-info" role="alert">' . htmlspecialchars($message) . '</div>';
}

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch extension requests
$sql = "SELECT br.*, u.name AS user_name, b.book_name
        FROM book_requests br
        JOIN users u ON br.user_id = u.id
        JOIN books b ON br.book_id = b.book_id
        WHERE br.extend_requested = 1
        ORDER BY br.request_date DESC";

$result = $conn->query($sql);

// Check if query was successful
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extension Requests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background-image: url('../images/adm5.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .table {
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .table td, .table th {
            padding: 12px;
            text-align: left;
        }

        .table td button {
            margin-right: 5px;
        }

        .btn-custom {
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            padding: 8px 12px;
        }

        .btn-approve {
            background-color: #28a745;
            color: white;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .btn-reject:hover {
            background-color: #c82333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header .btn-back {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
        }

        .header .btn-back:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="header">
        <h2>Extension Requests</h2>
        <a href="view_book_requests.php" class="btn btn-back btn-custom"><i class="fas fa-arrow-left"></i> Back to Book Requests</a>
    </div>
    
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Book Name</th>
                <th>User Name</th>
                <th>Request Date</th>
                <th>New Return Date</th>
                <th>Extension Reason</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['request_id']; ?></td>
                <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                <td><?php echo htmlspecialchars($row['request_date']); ?></td>
                <td><?php echo htmlspecialchars($row['new_return_date']) . ' (Adding 3 minutes to the current time)'; ?></td>

                <td><?php echo htmlspecialchars($row['extension_reason']); ?></td>
                <td>
                    <form action="process_extension_request.php" method="post" class="d-inline" onsubmit="return confirmAction(this);">
                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                        <button type="submit" name="action" value="approve" class="btn btn-approve btn-custom">Approve</button>
                        <br><br>
                        <button type="submit" name="action" value="reject" class="btn btn-reject btn-custom">Reject</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function confirmAction(form) {
    var action = form.action.value;
    var message = "";
    if (action == "approve") {
        message = "Are you sure you want to approve this extension request?";
    } else if (action == "reject") {
        message = "Are you sure you want to reject this extension request?";
    }
    return confirm(message);
}
</script>
</body>
</html>
