<?php
include 'db_config.php';

$category = $_GET['category'] ?? '';

if (!$category) {
    die("Invalid category.");
}

$query = "SELECT * FROM services WHERE category = :category";
$stmt = $conn->prepare($query);
$stmt->bindParam(':category', $category, PDO::PARAM_STR);
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category); ?> Services</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <a href="/">Home</a>
    </nav>
</header>

<main>
    <section class="services">
        <h2><?php echo htmlspecialchars($category); ?></h2>
        <div class="service-cards">
            <?php foreach ($services as $service): ?>
                <div class="card">
                    <a href="service.php?id=<?php echo $service['id']; ?>">
                        <img src="uploads/<?php echo htmlspecialchars($service['image']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>">
                        <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

</body>
</html>
