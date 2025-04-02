<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $service_id = $_POST['service_id'];
    $title = $_POST['service_title'];
    $category = $_POST['service_category'];
    $price = $_POST['service_price'];
    $description = $_POST['service_description'];

    // Fetch current service to check ownership
    $query = "SELECT * FROM services WHERE id = :service_id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute([':service_id' => $service_id, ':user_id' => $user_id]);
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        die("Unauthorized action.");
    }

    $image_name = $service['service_image']; // Keep old image if no new one is uploaded

    // Handle image upload
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == 0) {
        $upload_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES['service_image']['name']);
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['service_image']['tmp_name'], $target_file)) {
            // Delete old image if it exists
            if (!empty($service['service_image']) && file_exists("uploads/" . $service['service_image'])) {
                unlink("uploads/" . $service['service_image']);
            }
        } else {
            die("Error uploading image.");
        }
    }

    // Update the service in the database
    $updateQuery = "UPDATE services SET title = :title, category = :category, price = :price, 
                    description = :description, service_image = :service_image WHERE id = :service_id AND user_id = :user_id";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([
        ':title' => $title,
        ':category' => $category,
        ':price' => $price,
        ':description' => $description,
        ':service_image' => $image_name,
        ':service_id' => $service_id,
        ':user_id' => $user_id
    ]);

    header("Location: servicemain.php");
    exit();
} else {
    die("Invalid request.");
}
?>