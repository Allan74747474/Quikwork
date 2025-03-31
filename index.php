

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
    <title>QuikWork</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">QuikWork</div>
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

    <main>
        <section class="hero" id="hero1">
            <h1 class="ftp">Find the Perfect Freelance Services</h1>
            <div class="search-bar">
                <input type="text" placeholder="What service are you looking for?">
                <button>Search</button>
            </div>
        </section>
        <section id="services" class="services">
            <h2>Explore Popular Services</h2>
            <div class="service-cards">
                <div class="card">
                    <img src="https://blog-frontend.envato.com/cdn-cgi/image/width=1200,quality=75,format=auto,fit=crop,height=630/uploads/sites/2/2023/02/Tuts_Roundup__Top_Graphic_Design_Courses.jpeg" alt="Graphic Design">
                    <h3>Graphic Design</h3>
                </div>
                <div class="card">
                    <img src="https://media.istockphoto.com/id/1061031056/photo/pre-adolescent-boy-programming-at-computer.jpg?s=612x612&w=0&k=20&c=ZpdMz3WOKlahnBBOzeue4fdSIyzlZyHyfW4t9qi_xHQ=" alt="Programming">
                    <h3>Programming</h3>
                </div>
                <div class="card">
                    <img src="https://www.simplilearn.com/ice9/free_resources_article_thumb/How_To_Become_A_Content_Writer.jpg" alt="Content Writing">
                    <h3>Content Writing</h3>
                </div>
            </div>
        </section>
        <section id="about" class="about">
            <h2>About Us</h2>
            <p>QuikWork is a platform that connects talented freelancers with clients worldwide, providing high-quality services to meet your needs.</p>
        </section>
        <section id="contact" class="contact">
            <h2>Contact Us</h2>
            <form id="contact-form">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>


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
    <title>QuikWork</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">QuikWork</div>
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

    <main>
        <section class="hero" id="hero1">
            <h1 class="ftp">Find the Perfect Freelance Services</h1>
            <div class="search-bar">
                <input type="text" placeholder="What service are you looking for?">
                <button>Search</button>
            </div>
        </section>
        <section id="services" class="services">
            <h2>Explore Popular Services</h2>
            <div class="service-cards">
                <div class="card">
                    <img src="https://blog-frontend.envato.com/cdn-cgi/image/width=1200,quality=75,format=auto,fit=crop,height=630/uploads/sites/2/2023/02/Tuts_Roundup__Top_Graphic_Design_Courses.jpeg" alt="Graphic Design">
                    <h3>Graphic Design</h3>
                </div>
                <div class="card">
                    <img src="https://media.istockphoto.com/id/1061031056/photo/pre-adolescent-boy-programming-at-computer.jpg?s=612x612&w=0&k=20&c=ZpdMz3WOKlahnBBOzeue4fdSIyzlZyHyfW4t9qi_xHQ=" alt="Programming">
                    <h3>Programming</h3>
                </div>
                <div class="card">
                    <img src="https://www.simplilearn.com/ice9/free_resources_article_thumb/How_To_Become_A_Content_Writer.jpg" alt="Content Writing">
                    <h3>Content Writing</h3>
                </div>
            </div>
        </section>
        <section id="about" class="about">
            <h2>About Us</h2>
            <p>QuikWork is a platform that connects talented freelancers with clients worldwide, providing high-quality services to meet your needs.</p>
        </section>
        <section id="contact" class="contact">
            <h2>Contact Us</h2>
            <form id="contact-form">
                <input type="text" placeholder="Your Name" required>
                <input type="email" placeholder="Your Email" required>
                <textarea placeholder="Your Message" required></textarea>
                <button type="submit">Send</button>
            </form>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
