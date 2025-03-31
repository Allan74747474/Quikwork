<?php
session_start();
include 'db.php'; // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $userId = $_SESSION['user_id'];
    $targetDir = "uploads/"; // Folder where images are stored
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allowed file types
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
            // Update database
            $updateQuery = "UPDATE users SET profile_pic = '$fileName' WHERE id = '$userId'";
            mysqli_query($conn, $updateQuery);

            // Update session
            $_SESSION['profile_pic'] = $fileName;

            header("Location: profile.php?success=1");
            exit();
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
    }
}
?>
