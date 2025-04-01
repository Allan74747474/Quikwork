<?php
session_start();
include 'db_config.php';
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
}
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$service_id = $_GET['id'];


// Fetch service details
$query = "SELECT * FROM services WHERE id = :service_id AND user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->execute([':service_id' => $service_id, ':user_id' => $user_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    die("Service not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <link rel="stylesheet" href="mainservice.css">
    <link rel="icon" type="image/png" href="favicon.png">
    
</head>
<body>
    <nav class="navbar">
        <!-- Logo as an image instead of text -->
        <div class="logo">
            <img src="uploads/logo.png" alt="QuikWork Logo" style="height: 50px; width: auto;">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="servicemain.php">Services</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="contact.php">Contact</a></li>

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
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <!-- Show Login Link If Not Logged In -->
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<div class ="main-content">
 <section>
     <h2>Create and Offer Your Services</h2>
        <form action="update_service.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="service_id" value="<?php echo htmlspecialchars($service['id']); ?>">
        
        <label>Title:</label>
        <input type="text" name="service_title" value="<?php echo htmlspecialchars($service['title']); ?>" required>

        <label>Category:</label>
        <select name="service_category" required>
            <option value="graphic-design" <?php if ($service['category'] == 'graphic-design') echo 'selected'; ?>>Graphic Design</option>
            <option value="web-development" <?php if ($service['category'] == 'web-development') echo 'selected'; ?>>Web Development</option>
            <option value="content-writing" <?php if ($service['category'] == 'content-writing') echo 'selected'; ?>>Content Writing</option>
            <option value="digital-marketing" <?php if ($service['category'] == 'digital-marketing') echo 'selected'; ?>>Digital Marketing</option>
        </select>

        <label>Price ($):</label>
        <input type="number" name="service_price" value="<?php echo htmlspecialchars($service['price']); ?>" required>

        <label>Description:</label>
        <textarea name="service_description" required><?php echo htmlspecialchars($service['description']); ?></textarea>

        <label>Upload New Image (optional):</label>
        <input type="file" name="service_image" accept="image/*">

        <button type="submit">Update Service</button>
    </form>
    </section>
</div>
</body>
</html>
