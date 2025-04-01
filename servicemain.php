<?php
session_start();
include 'db_config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<script>alert('You must log in to access this page!'); window.location.href='login.php';</script>";
    exit();
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
    <title>QuikWork - Your Services</title>
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

<div class="main-content">
    <section>
        <h2>Create and Offer Your Services</h2>
        <form action="save_service.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="service-title">Service Title</label>
                <input type="text" id="service-title" name="service_title" placeholder="Enter your service title" required>
            </div>

            <div class="form-group">
                <label for="service-category">Category</label>
                <select id="service-category" name="service_category" required>
                    <option value="">--Select a category--</option>
                    <option value="graphic-design">Graphic Design</option>
                    <option value="web-development">Web Development</option>
                    <option value="content-writing">Content Writing</option>
                    <option value="digital-marketing">Digital Marketing</option>
                </select>
            </div>

            <div class="form-group">
                <label for="service-price">Price ($)</label>
                <input type="number" id="service-price" name="service_price" placeholder="Enter your price" required>
            </div>

            <div class="form-group">
                <label for="service-description">Description</label>
                <textarea id="service-description" name="service_description" rows="5" placeholder="Describe your service in detail" required></textarea>
            </div>

            <div class="form-group">
                <label for="service-image">Upload an Image</label>
                <input type="file" id="service-image" name="service_image" accept="image/*" required>
            </div>

            <button class="submit" type="submit">Create Service</button>
        </form>
    </section>
</div>

<div class="main-content">
    <section>
        <h2>Your Services</h2>
        <p>Below is a list of all the services you have created.</p>

        <div class="service-list">
            <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                    <div class="service">
                        <img src="uploads/<?php echo htmlspecialchars($service['service_image']); ?>" alt="Service Image">
                        <h4><?php echo htmlspecialchars($service['title']); ?></h4>
                        <p>Category: <?php echo htmlspecialchars($service['category']); ?></p>
                        <p>Price: $<?php echo htmlspecialchars($service['price']); ?></p>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                        <form action="delete_service.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this service?');">
                            <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
                             <button type="submit">Delete</button>
                             <a href="edit_service.php?id=<?php echo $service['id']; ?>">Edit</a>

                        </form>

                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No services found. Create your first service above!</p>
            <?php endif; ?>
        </div>
    </section>
</div>

</body>
</html>
