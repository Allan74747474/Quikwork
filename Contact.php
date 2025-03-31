<?php
session_start();
include 'db_config.php'; 

$user_id = $_SESSION['user_id'] ?? null;
$profile_pic = "default.jpg";
$username = "Guest";

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
    <title>Contact Us - QuikWork</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="contact.png">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">QuikWork</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="servicemain.php">Services</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if ($user_id): ?>
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
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section class="contact-page">
            <h1>Contact Us</h1>
            <p>Have questions? Feel free to reach out to us. We're happy to help!</p>
            
            <div class="contact-container">
                <form id="contact-form">
                    <label for="name">Your Name</label>
                    <input type="text" id="name" placeholder="Enter your name" required>

                    <label for="email">Your Email</label>
                    <input type="email" id="email" placeholder="Enter your email" required>

                    <label for="message">Your Message</label>
                    <textarea id="message" placeholder="Enter your message" required></textarea>

                    <button type="submit">Send Message</button>
                </form>

                <div class="contact-info">
                    <h3>Our Contact Information</h3>
                    <p>Email: support@quikwork.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <p>Address: 123 Freelancer Street, Remote City, Web</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
