<?php
session_start();
include 'db_config.php';  // Ensure this file correctly sets up a PDO connection ($conn)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Validate username and email
    if (empty($username) || empty($email)) {
        header("Location: profile.php?error=empty_fields");
        exit();
    }

    try {
        // Update user details using PDO
        $updateQuery = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':id' => $userId
        ]);

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
                    // Update the profile picture in the database
                    $updatePicQuery = "UPDATE users SET profile_pic = :profile_pic WHERE id = :id";
                    $stmt = $conn->prepare($updatePicQuery);
                    $stmt->execute([
                        ':profile_pic' => $newFileName,
                        ':id' => $userId
                    ]);

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
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
