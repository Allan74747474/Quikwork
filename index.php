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
            <li><a href="#about">About</a></li>
            <li><a href="contact">Contact</a></li>

            <?php if ($user_id): ?>
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
                <li><a href="login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>
    <section class="hero" id="hero1">
        <h1 class="ftp">Find the Perfect Freelance Services</h1>
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="query" placeholder="What service are you looking for?" value="<?php echo htmlspecialchars($_GET['query'] ?? ''); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </section>

    <?php
    // Handle search query
    $search_query = $_GET['query'] ?? '';
    if ($search_query) {
        $query = "SELECT * FROM services WHERE title LIKE :search_query OR description LIKE :search_query";
        $stmt = $conn->prepare($query);
        $search_param = '%' . $search_query . '%';
        $stmt->bindParam(':search_query', $search_param, PDO::PARAM_STR);
        $stmt->execute();
        $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $search_results = [];
    }
    ?>

    <?php if ($search_query): ?>
        <section class="search-results">
            <h2>Search Results</h2>
            <?php if ($search_results): ?>
                <ul>
                    <?php foreach ($search_results as $service): ?>
                        <li>
                            <a href="service.php?id=<?php echo $service['id']; ?>">
                                <?php echo htmlspecialchars($service['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <section id="services" class="services">
        <h2>Explore Popular Services</h2>
        <div class="service-cards">
            <div class="card">
                <a href="service.php?id=1">
                    <img src="https://blog-frontend.envato.com/cdn-cgi/image/width=1200,quality=75,format=auto,fit=crop,height=630/uploads/sites/2/2023/02/Tuts_Roundup__Top_Graphic_Design_Courses.jpeg" alt="Graphic Design">
                    <h3>Graphic Design</h3>
                </a>
            </div>
            <div class="card">
                <a href="service.php?id=2">
                    <img src="https://media.istockphoto.com/id/1061031056/photo/pre-adolescent-boy-programming-at-computer.jpg?s=612x612&w=0&k=20&c=ZpdMz3WOKlahnBBOzeue4fdSIyzlZyHyfW4t9qi_xHQ=" alt="Programming">
                    <h3>Programming</h3>
                </a>
            </div>
            <div class="card">
                <a href="service.php?id=3">
                    <img src="https://www.simplilearn.com/ice9/free_resources_article_thumb/How_To_Become_A_Content_Writer.jpg" alt="Content Writing">
                    <h3>Content Writing</h3>
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <h2>About Us</h2>
        <p>QuikWork is a platform that connects talented freelancers with clients worldwide, providing high-quality services to meet your needs.</p>
    </section>
</main>

<footer>
    <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
</footer>

<script src="script.js"></script>
</body>
</html>
