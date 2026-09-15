<?php
session_start(); // Ensure the session is started

include('../admin/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['payment_action'])) {
    $request_id = $_POST['request_id'];
    $payment_action = $_POST['payment_action'];

    // Update the payment_verified status based on the action
    $payment_verified = ($payment_action === 'verify') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE book_requests SET payment_verified = ? WHERE request_id = ?");
    $stmt->bind_param('ii', $payment_verified, $request_id);

    if ($stmt->execute()) {
        // Update session warning based on payment verification
        if ($payment_verified) {
            $_SESSION['warning'] = 'Reminder sent to you for overdue book requests.';
            $_SESSION['hide_warning'] = false; // Ensure the warning is visible
        } else {
            unset($_SESSION['warning']); // Clear the warning if payment is unverified
            $_SESSION['hide_warning'] = true; // Hide warning when payment is not verified
        }

        // Return a JSON response
        echo json_encode(['success' => true, 'payment_verified' => $payment_verified]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
