<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Fetch user's current password securely
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo "User not found.";
        exit();
    }

    // Verify current password
    if (password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $confirmNewPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password securely
            $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $hashedPassword, $userId);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: profile.php?password_updated=1");
                exit();
            } else {
                echo "Error updating password.";
            }
        } else {
            echo "New passwords do not match.";
        }
    } else {
        echo "Current password is incorrect.";
    }
}
?>
