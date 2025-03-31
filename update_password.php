<?php
session_start();
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$userId = $_SESSION['user_id'];
$currentPassword = $_POST['current_password'];
$newPassword = $_POST['new_password'];
$confirmNewPassword = $_POST['confirm_new_password'];

try {
    // Fetch current password securely
    $query = "SELECT password FROM users WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$user) {
        die("User not found.");
    }

    // Verify current password
    if (password_verify($currentPassword, $user['password'])) {
        if ($newPassword === $confirmNewPassword) {
            // Hash the new password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password securely
            $updateQuery = "UPDATE users SET password = :password WHERE id = :id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->closeCursor();

            header("Location: profile.php?password_updated=1");
            exit();
        } else {
            die("New passwords do not match.");
        }
    } else {
        die("Current password is incorrect.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
