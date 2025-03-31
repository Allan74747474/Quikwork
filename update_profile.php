<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Update user details
    $updateQuery = "UPDATE users SET username='$username', email='$email' WHERE id='$userId'";
    mysqli_query($conn, $updateQuery);

    // Handle profile picture upload
    if (!empty($_FILES["profile_pic"]["name"])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES["profile_pic"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
                $updatePicQuery = "UPDATE users SET profile_pic = '$fileName' WHERE id = '$userId'";
                mysqli_query($conn, $updatePicQuery);
                $_SESSION['profile_pic'] = $fileName; // Update session
            }
        }
    }

    header("Location: profile.php?success=1");
    exit();
}
?>
