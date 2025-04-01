<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact.css">
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="servicemain.php">Services</a>
    <a href="contact.php">Contact</a>
</nav>

<div class="container">
    <h2>Contact Us</h2>
    <form action="send_contact.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="message">Message:</label>
        <textarea name="message" rows="5" required></textarea>

        <button type="submit">Send</button>
    </form>
</div>

</body>
</html>
