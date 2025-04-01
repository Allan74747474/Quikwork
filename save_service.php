<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['service_title'];
    $category = $_POST['service_category'];
    $price = $_POST['service_price'];
    $description = $_POST['service_description'];
    $image_name = "";

    // Handle file upload
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == 0) {
        $upload_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES['service_image']['name']);
        $target_file = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['service_image']['tmp_name'], $target_file)) {
            die("Error uploading image.");
        }
    }

    // Insert service into database
    try {
        $query = "INSERT INTO services (user_id, title, category, price, description, service_image) 
                  VALUES (:user_id, :title, :category, :price, :description, :service_image)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':user_id' => $user_id,
            ':title' => $title,
            ':category' => $category,
            ':price' => $price,
            ':description' => $description,
            ':service_image' => $image_name
        ]);
        header("Location: servicemain.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
