<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    try {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "Username or email already exists!";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            header("Location: login.php");
            exit();

        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
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
                <li><a href="/">Home</a></li>
                <li><a href="servicemain">Services</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="contact">Contact</a></li>
                <li><a href="login">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Register</h2>
        <form method="POST" action="">
            <label>Username:</label>
            <input type="text" name="username" required><br>

            <label>Email:</label>
            <input type="email" name="email" required><br>

            <label>Password:</label>
            <input type="password" name="password" required><br>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
