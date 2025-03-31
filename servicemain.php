<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuikWork - All Services</title>
    <link rel="stylesheet" href="mainservice.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
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


<nav class="navbar">
    <h1>QuikWork</h1>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="servicemain.php">Services</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="Contact.php">Contact</a></li>

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


<div class="main-content">
        <section>
            <h2>Create and Offer Your Services</h2>
            <form id="service-form">
                <div class="form-group">
                    <label for="service-title">Service Title</label>
                    <input type="text" id="service-title" name="service-title" placeholder="Enter your service title" required>
                </div>

                <div class="form-group">
                    <label for="service-category">Category</label>
                    <select id="service-category" name="service-category" required>
                        <option value="">--Select a category--</option>
                        <option value="graphic-design">Graphic Design</option>
                        <option value="web-development">Web Development</option>
                        <option value="content-writing">Content Writing</option>
                        <option value="digital-marketing">Digital Marketing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="service-price">Price ($)</label>
                    <input type="number" id="service-price" name="service-price" placeholder="Enter your price" required>
                </div>

                <div class="form-group">
                    <label for="service-description">Description</label>
                    <textarea id="service-description" name="service-description" rows="5" placeholder="Describe your service in detail" required></textarea>
                </div>

                <div class="form-group">
                    <label for="service-image">Upload an Image</label>
                    <input type="file" id="service-image" name="service-image" accept="image/*" required>
                </div>

                <button class="submit" type="submit">Create Service</button>
            </form>
        </section>
    </div>

    <div class="main-content" id="main">
        <section>
            <h2>All Your Services</h2>
            <p>Below is a list of all the services you have created. Manage and update them as needed.</p>
        </section>
    </div>

    <script src="service.js"></script>

    <!-- Footer -->
    <!-- Uncomment if needed
    <footer>
        <p>&copy; 2025 QuikWork. All rights reserved.</p>
    </footer>
    -->
</body>
</html>