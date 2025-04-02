<?php
session_start();
include 'db_config.php'; // Ensure database connection

// Check if 'id' parameter exists in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid service ID.");
}

$service_id = $_GET['id'];

// Fetch service details from the database
$query = "SELECT s.*, u.username, u.email FROM services s 
          JOIN users u ON s.user_id = u.id 
          WHERE s.id = :service_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
$stmt->execute();
$service = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$service) {
    die("Service not found.");
}
?>

<?php
session_start();
include 'db_config.php'; // Database connection

$user_id = $_SESSION['user_id'] ?? null;
$profile_pic = "default.jpg"; // Default profile image
$username = "Guest"; // Default username

if ($user_id) {
    // Fetch user details
    $query = "SELECT username, profile_pic FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $username = $user['username'];
        if (!empty($user['profile_pic'])) {
            $profile_pic = $user['profile_pic'];
        }
    }

    // Fetch user services
    $serviceQuery = "SELECT * FROM services WHERE user_id = :user_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($serviceQuery);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($service['title']); ?> - QuikWork</title>
    <link rel="stylesheet" href="service.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>

<header>
 <nav class="navbar">
        <!-- Logo as an image instead of text -->
        <div class="logo">
            <img src="uploads/logo.png" alt="QuikWork Logo" style="height: 50px; width: auto;">
        </div>
        <ul class="nav-links">
            <li><a href="/">Home</a></li>
            <li><a href="servicemain">Services</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact">Contact</a></li>
              <?php if ($user_id): ?>
                <!-- User Profile Dropdown -->
                <li class="dropdown">
                    <a href="#" class="dropbtn">
                        <img src="uploads/<?php echo htmlspecialchars($profile_pic); ?>" 
                             alt="Profile" class="nav-profile-pic"
                             style="height:40px; width:40px;">
                        <?php echo htmlspecialchars($username); ?>
                    </a>
                    <div class="dropdown-content">
                        <a href="profile">Profile</a>
                        <a href="logout">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <!-- Show Login Link If Not Logged In -->
                <li><a href="login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

</header>

<main>
    <h1><?php echo htmlspecialchars($service['title']); ?></h1>
    
  <?php if (!empty($service['service_image'])): ?>

     <img src="uploads/<?php echo htmlspecialchars($service['service_image']); ?>" alt="Service Image">
<?php else: ?>
    <p>No image available</p>
<?php endif; ?>


    <p><strong>Category:</strong> <?php echo htmlspecialchars($service['category']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($service['price'], 2); ?></p>
    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($service['description'])); ?></p>
    
    <h3>Contact the Seller</h3>
    <p><strong>Seller:</strong> <?php echo htmlspecialchars($service['username']); ?></p>
    <p><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($service['email']); ?>">
        <?php echo htmlspecialchars($service['email']); ?>
    </a></p>
</main>

<footer>
    <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
</footer>

</body>
</html>
