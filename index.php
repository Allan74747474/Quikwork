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

// Fetch all services on page load for instant search
$query = "SELECT id, title FROM services";
$stmt = $conn->prepare($query);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <input type="text" id="search-input" name="query" placeholder="What service are you looking for?" autocomplete="off">
                <button type="submit">Search</button>
                <div id="search-results" class="dropdown-results"></div>
            </form>
        </div>
    </section>

    <section id="services" class="services">
        <h2>Explore Popular Services</h2>
        <div class="service-cards">
            <div class="card">
               <a href="service_cat.php?category=Graphic-Design">

                    <img src="https://blog-frontend.envato.com/cdn-cgi/image/width=1200,quality=75,format=auto,fit=crop,height=630/uploads/sites/2/2023/02/Tuts_Roundup__Top_Graphic_Design_Courses.jpeg" alt="Graphic Design">
                    <h3>Graphic Design</h3>
                </a>
            </div>
            <div class="card">
                <a href="service_cat.php?category=Programming">
                    <img src="https://media.istockphoto.com/id/1061031056/photo/pre-adolescent-boy-programming-at-computer.jpg?s=612x612&w=0&k=20&c=ZpdMz3WOKlahnBBOzeue4fdSIyzlZyHyfW4t9qi_xHQ=" alt="Programming">
                    <h3>Programming</h3>
                </a>
            </div>
            <div class="card">
                <a href="service_cat.php?category=Content-Writing">
                    <img src="https://www.simplilearn.com/ice9/free_resources_article_thumb/How_To_Become_A_Content_Writer.jpg" alt="Content Writing">
                    <h3>Content Writing</h3>
                </a>
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <h2>About Us</h2>
        <p>QuikWork is a global freelance platform designed 
to connect skilled professionals with job 
opportunities efficiently. By leveraging its 
extensive network of industry experts, QuikWork 
matches freelancers with high-quality projects that 
suit their abilities. This platform empowers 
freelancers by offering diverse opportunities while 
supporting businesses in completing their tasks 
swiftly and with professionalism. With its 
emphasis on speed and reliability, QuikWork 
ensures seamless collaborations and exceptional 
results for all parties involved.</p>
    </section>


<section id="service" class="services">
    <h2>More Services</h2>
    <div class="service-cards">
        <div class="card">
            <a href="service_cat.php?category=Web-Development">
                <img src="https://brewingideaz.com/wp-content/uploads/2024/01/Web-Designer-2.jpg" alt="Web Devlopment"> 
                <h3>Web Development </h3>
            </a>
        </div>
    </div>
</section>

</main>

<footer>
    <p>&copy; 2025 QuikWork. All Rights Reserved.</p>
</footer>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-input");
        const searchResults = document.getElementById("search-results");

        // Preloaded services from PHP
        const services = <?php echo json_encode($services); ?>;

        searchInput.addEventListener("keyup", function () {
            let query = searchInput.value.toLowerCase().trim();
            searchResults.innerHTML = "";

            if (query.length === 0) {
                searchResults.style.display = "none";
                return;
            }

            let filteredServices = services.filter(service =>
                service.title.toLowerCase().includes(query)
            );

            if (filteredServices.length > 0) {
                searchResults.style.display = "block";
                filteredServices.forEach(service => {
                    let item = document.createElement("div");
                    item.classList.add("result-item");
                    item.innerHTML = `<a href="service.php?id=${service.id}">${service.title}</a>`;
                    searchResults.appendChild(item);
                });
            } else {
                searchResults.innerHTML = "<div class='result-item'>No results found</div>";
                searchResults.style.display = "block";
            }
        });

        document.addEventListener("click", function (e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.style.display = "none";
            }
        });
    });
</script>

