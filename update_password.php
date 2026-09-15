<?php
ob_start();
session_start();

$connection = mysqli_connect("localhost", "root", "", "lms");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Password reset flow
    if (isset($_POST['token'])) {
        $token = mysqli_real_escape_string($connection, $_POST['token']);
        $new_password = mysqli_real_escape_string($connection, $_POST['password']);

        // Verify the token and check if it's not expired
        $query = "SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()";
        $stmt = mysqli_prepare($connection, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $token);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $email);
            mysqli_stmt_fetch($stmt);

            if ($email) {
                // Free the result set from the first query
                mysqli_stmt_free_result($stmt);
                mysqli_stmt_close($stmt);  // Close statement

                // Hash the new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the user's password
                $update_query = "UPDATE users SET password = ? WHERE email = ?";
                $stmt_update = mysqli_prepare($connection, $update_query);
                if ($stmt_update) {
                    mysqli_stmt_bind_param($stmt_update, "ss", $hashed_new_password, $email);
                    if (mysqli_stmt_execute($stmt_update)) {
                        // Delete the token
                        $delete_query = "DELETE FROM password_resets WHERE token = ?";
                        $stmt_delete = mysqli_prepare($connection, $delete_query);
                        if ($stmt_delete) {
                            mysqli_stmt_bind_param($stmt_delete, "s", $token);
                            mysqli_stmt_execute($stmt_delete);
                            mysqli_stmt_close($stmt_delete);  // Close the delete statement
                        }

                        echo '<script type="text/javascript">
                                alert("Password updated successfully.");
                                window.location.href = "index.php";
                              </script>';
                        exit();
                    } else {
                        echo '<script type="text/javascript">
                                alert("Error updating password.");
                                window.location.href = "reset_password.php?token=' . htmlspecialchars($token) . '";
                              </script>';
                        exit();
                    }
                    mysqli_stmt_close($stmt_update);  // Close update statement
                } else {
                    echo '<script type="text/javascript">
                            alert("Error preparing update query.");
                            window.location.href = "reset_password.php?token=' . htmlspecialchars($token) . '";
                          </script>';
                    exit();
                }
            } else {
                echo '<script type="text/javascript">
                        alert("Invalid or expired token.");
                        window.location.href = "forgot_password.php";
                      </script>';
                exit();
            }
            // Close the initial prepared statement
            mysqli_stmt_close($stmt);
        } else {
            echo '<script type="text/javascript">
                    alert("Error preparing select query.");
                    window.location.href = "forgot_password.php";
                  </script>';
            exit();
        }
    } 
    // Logged-in user password change flow
    else {
        if (!isset($_SESSION['email'])) {
            echo '<script type="text/javascript">
                    alert("Session expired. Please log in again.");
                    window.location.href = "index.php";
                  </script>';
            exit();
        }

        $email = $_SESSION['email'];
        $current_password = mysqli_real_escape_string($connection, $_POST['current_password']);
        $new_password = mysqli_real_escape_string($connection, $_POST['new_password']);

        $query = "SELECT password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($connection, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $hashed_password);
            mysqli_stmt_fetch($stmt);

            if (password_verify($current_password, $hashed_password)) {
                // Free the result from the first query
                mysqli_stmt_free_result($stmt);
                mysqli_stmt_close($stmt);  // Close the first statement

                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query = "UPDATE users SET password = ? WHERE email = ?";
                $stmt_update = mysqli_prepare($connection, $update_query);
                if ($stmt_update) {
                    mysqli_stmt_bind_param($stmt_update, "ss", $hashed_new_password, $email);
                    if (mysqli_stmt_execute($stmt_update)) {
                        echo '<script type="text/javascript">
                                alert("Password updated successfully.");
                                window.location.href = "user_dashboard.php";
                              </script>';
                        exit();
                    } else {
                        echo '<script type="text/javascript">
                                alert("Error updating password.");
                                window.location.href = "change_password.php";
                              </script>';
                        exit();
                    }
                    mysqli_stmt_close($stmt_update);  // Close the update statement
                } else {
                    echo '<script type="text/javascript">
                            alert("Error preparing update query.");
                            window.location.href = "change_password.php";
                          </script>';
                    exit();
                }
            } else {
                echo '<script type="text/javascript">
                        alert("Wrong current password.");
                        window.location.href = "change_password.php";
                      </script>';
                exit();
            }
            // Close the initial prepared statement
            mysqli_stmt_close($stmt);
        } else {
            echo '<script type="text/javascript">
                    alert("Error preparing select query.");
                    window.location.href = "change_password.php";
                  </script>';
            exit();
        }
    }
} else {
    echo '<script type="text/javascript">
            alert("Form submission error.");
            window.location.href = "change_password.php";
          </script>';
    exit();
}

mysqli_close($connection);
?>