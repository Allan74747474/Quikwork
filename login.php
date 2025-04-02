<?php
session_start();
include 'db_config.php';
$_SESSION['user_id'] = $user_id;  // Store user ID in session
$login_error = "";
setcookie("user_id", $user_id, time() + (86400 * 30), "/"); // 30 days


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $stmt = $conn->prepare("SELECT id, username, password, profile_pic FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_pic'] = !empty($user['profile_pic']) ? $user['profile_pic'] : 'https://www.w3schools.com/howto/img_avatar.png';

            header("Location: /");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
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
                <li><a href="login">Login</a></li>
            </ul>
        </nav>
    </header>

    <h2>Login</h2>

    <!-- Show login error message -->
    <?php if (!empty($login_error)): ?>
        <p style="color: red;"><?php echo $login_error; ?></p>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Login</button>
        Not a member yet? Click here to  
        <a href="register" class="register-btn">Register</a>
    </form>
</body>
</html>
