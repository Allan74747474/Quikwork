<?php
session_start();
include 'db_config.php'; // Ensure database connection

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("You must be logged in to upload a profile picture.");
}

if (isset($_POST['upload']) && isset($_FILES['profile_pic'])) {
    $upload_dir = "uploads/";
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    $file_name = basename($_FILES["profile_pic"]["name"]);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (!in_array($file_ext, $allowed_types)) {
        die("Invalid file type! Only JPG, JPEG, PNG, and GIF allowed.");
    }

    $new_file_name = "profile_" . $user_id . "." . $file_ext; // Unique name
    $target_path = $upload_dir . $new_file_name;

    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_path)) {
        // Update profile picture in database
        $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_file_name, $user_id);
        $stmt->execute();
        $stmt->close();

        // Update session with new profile pic
        $_SESSION['profile_pic'] = $new_file_name;

        echo "Profile picture updated successfully!";
        header("Location: profile.php");
    } else {
        echo "Error uploading file.";
    }
}
?>
