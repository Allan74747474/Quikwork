<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_config.php';

echo "Debugging started!<br>";

if (!isset($conn)) {
    die("Database connection is missing!");
}

// Stop script to check if this part runs
exit("Checkpoint reached!");
?>


<?php
session_start();
echo "Script started!";
exit();
?>


<?php
session_start();
include 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

die("Script started!"); // Debug point 1

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    die("POST request received!"); // Debug point 2

    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        die("Error: Fields are empty");
    }

    if ($newPassword !== $confirmNewPassword) {
        die("Error: Passwords do not match");
    }

    try {
        die("Checking user password"); // Debug point 3

        $query = "SELECT password FROM users WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die("Error: User not found");
        }

        if (!password_verify($currentPassword, $user['password'])) {
            die("Error: Incorrect current password");
        }

        die("Password verified, updating..."); // Debug point 4

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $updateQuery = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId
        ]);

        die("Password updated successfully! Redirecting..."); // Debug point 5
        header("Location: profile.php?success=password_updated");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Error: Invalid request method or user not logged in");
}
