

<?php
session_start();
include 'db_config.php'; // Ensure database connection

$user_id = $_SESSION['user_id'] ?? null;
$profile_pic = "default.jpg"; // Default profile image
$username = "Guest"; // Default username

if ($user_id) {
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>

 <!-- Navbar -->
    <header>
        <nav class="navbar">
        <div class="logo">
            <img src="uploads/logo.png" alt="QuikWork Logo" style="height: 50px; width: auto;">
        </div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="servicemain.php">Services</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
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
<div class="container">
    <h2>Contact Us</h2>
    <form action="send_contact.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="message">Message:</label>
        <textarea name="message" rows="5" required></textarea>

        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
