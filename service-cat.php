<?php
session_start();
include 'db_config.php'; // Database connection

// Check if 'category' parameter exists in the URL
if (!isset($_GET['category']) || empty($_GET['category'])) {
    die("Invalid category.");
}

$category = $_GET['category'];

// Fetch all services under the selected category
$query = "SELECT s.*, u.username, u.email FROM services s 
          JOIN users u ON s.user_id = u.id 
          WHERE s.category = :category";
$stmt = $conn->prepare($query);
$stmt->bindParam(':category', $category, PDO::PARAM_STR);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$services) {
    die("No services found in this category.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> Services - QuikWork</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="favicon.png">
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
            <li><a href="contact">Contact</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <img src="uploads/<?php echo $_SESSION['profile_pic'] ?? 'default.jpg'; ?>" 
                             alt="Profile" class="nav-profile-pic"
                             style="height:40px; width:40px;">
                        <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <div class="dropdown-content">
                        <a href="profile">Profile</a>
                        <a href="logout">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <h1><?php echo htmlspecialchars($category); ?> Services</h1>
    
    <div class="service-cards">
        <?php foreach ($services as $service): ?>
            <div class="card">
                <a href="service_detail.php?id=<?php echo $service['id']; ?>">
                    <img src="uploads/<?php echo htmlspecialchars($service['service_image'] ?: 'default.jpg'); ?>" alt="Service Image">
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    <p><strong>Price:</strong> $<?php echo number_format($service['price'], 2); ?></p>
                    <p><strong>Seller:</strong> <?php echo htmlspecialchars($service['username']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<footer>
    <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
</footer>

</body>
</html>
