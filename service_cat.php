<?php
session_start();
include 'db_config.php';

// Check if category is set in the URL
if (!isset($_GET['category']) || empty($_GET['category'])) {
    die("Invalid category.");
}

$category = $_GET['category'];

// Fetch services based on the selected category
$query = "SELECT s.*, u.username FROM services s 
          JOIN users u ON s.user_id = u.id 
          WHERE s.category = :category ORDER BY s.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':category', $category, PDO::PARAM_STR);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(ucwords(str_replace("-", " ", $category))); ?> Services</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">
            <img src="uploads/logo.png" alt="QuikWork Logo" style="height: 50px; width: auto;">
        </div>
        <ul class="nav-links">
            <li><a href="/">Home</a></li>
            <li><a href="servicemain">Services</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <h1><?php echo htmlspecialchars(ucwords(str_replace("-", " ", $category))); ?> Services</h1>

    <div class="service-cards">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <div class="card">
                    <a href="service.php?id=<?php echo $service['id']; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($service['service_image'] ?? 'default.jpg'); ?>" alt="Service Image">
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p>By: <?php echo htmlspecialchars($service['username']); ?></p>
                        <p>Price: $<?php echo number_format($service['price'], 2); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No services available in this category yet.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
</footer>

</body>
</html>
