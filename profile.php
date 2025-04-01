
<?php
session_start();
include 'db_config.php'; // Corrected include

// Ensure session is active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Fetch user details securely using PDO
$query = "SELECT username, email, profile_pic FROM users WHERE id = :userId";
$stmt = $conn->prepare($query);
$stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePic = !empty($user['profile_pic']) ? $user['profile_pic'] : "default.jpg";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">
            <img src="uploads/logo.png" alt="QuikWork Logo" style="height: 50px; width: auto;">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="servicemain.php">Services</a></li>
            <li class="dropdown">
                <a href="#" class="dropbtn">
                    <img src="uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Profile" class="nav-profile-pic">
                    <?php echo htmlspecialchars($user['username']); ?>
                </a>
                <div class="dropdown-content">
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<div class="main-content">
    <section class="profile-section">
        <h2>Your Profile</h2>
        <div class="profile-info">
            <img src="uploads/<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture" class="large-profile-pic">
            <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Change Profile Picture:</label>
                    <input type="file" name="profile_pic" accept="image/*">
                </div>

                <button class="submit" type="submit" name="update_profile">Update Profile</button>
            </form>
        </div>
    </section>

    <section class="password-section">
        <h3>Change Password</h3>
        <form action="update_password.php" method="POST">
            <div class="form-group">
                <label>Current Password:</label>
                <input type="password" name="current_password" required>
            </div>

            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" name="confirm_new_password" required>
            </div>

            <button class="submit" type="submit" name="update_password">Change Password</button>
        </form>
    </section>
</div>

<footer>
    <p>&copy; 2025 QuikWork. All rights reserved.</p>
</footer>

</body>
</html>