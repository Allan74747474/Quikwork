<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate username and email
    if (empty($username) || empty($email)) {
        header("Location: profile.php?error=empty_fields");
        exit();
    }

    // Secure database update using prepared statements
    $updateQuery = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssi", $username, $email, $userId);
    $stmt->execute();
    $stmt->close();

    // Handle profile picture upload
    if (!empty($_FILES["profile_pic"]["name"])) {
        $targetDir = "uploads/";

        // Generate a unique filename
        $fileExt = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
        $newFileName = "profile_" . $userId . "_" . time() . "." . $fileExt;
        $targetFilePath = $targetDir . $newFileName;

        // Allowed file types and max file size (2MB)
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        if (in_array($fileExt, $allowedTypes) && $_FILES["profile_pic"]["size"] <= $maxFileSize) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
                // Update the database with the new profile picture
                $updatePicQuery = "UPDATE users SET profile_pic = ? WHERE id = ?";
                $stmt = $conn->prepare($updatePicQuery);
                $stmt->bind_param("si", $newFileName, $userId);
                $stmt->execute();
                $stmt->close();

                // Update session variable
                $_SESSION['profile_pic'] = $newFileName;
            } else {
                header("Location: profile.php?error=upload_failed");
                exit();
            }
        } else {
            header("Location: profile.php?error=invalid_file");
            exit();
        }
    }

    header("Location: profile.php?success=1");
    exit();
}
?>
