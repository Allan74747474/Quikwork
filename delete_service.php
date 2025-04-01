<?php
session_start();
include 'db_config.php'; // Database connection

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];
    $user_id = $_SESSION['user_id'];

    // Ensure the service belongs to the logged-in user
    $query = "DELETE FROM services WHERE id = :service_id AND user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: servicemain.php?msg=Service Deleted Successfully");
        exit();
    } else {
        echo "Error deleting service.";
    }
}
?>
