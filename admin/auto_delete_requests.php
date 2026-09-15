<?php
include 'db_connect.php';  // Adjust path as needed

// Initialize success flag
$success = false;

// Check if user has confirmed the deletion
if (isset($_POST['confirm_delete'])) {
    // Auto delete requests older than 1 minute (for testing purposes)
    $delete_stmt = $conn->prepare("DELETE FROM book_requests WHERE request_date < NOW() - INTERVAL 1 MINUTE");

    if ($delete_stmt->execute()) {
        $success = true;
    } else {
        $success = false;
    }

    $delete_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Requests Status</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-image: url(../images/pexels-padrinan-2882553.jpg);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
            max-width: 600px;
        }
        .alert {
            font-size: 18px;
        }
        .btn-home {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- If user has confirmed, show the result of the deletion -->
    <?php if (isset($_POST['confirm_delete'])): ?>
        <?php if ($success): ?>
            <div id="statusMessage" class="alert alert-success" role="alert">
                Old book requests older than 1 minute have been deleted successfully.
            </div>
        <?php else: ?>
            <div id="statusMessage" class="alert alert-danger" role="alert">
                Error deleting old book requests. Please try again.
            </div>
        <?php endif; ?>
        <a href="view_book_requests.php" class="btn btn-primary btn-home">Back to Request table</a>
    <?php else: ?>
        <!-- Show confirmation modal on page load -->
        <button id="deleteBtn" class="btn btn-danger" data-toggle="modal" data-target="#confirmModal">Delete Old Requests</button>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete all book requests older than 1 minute?
                    </div>
                    <div class="modal-footer">
                        <form method="POST">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Adding jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
