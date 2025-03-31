<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
setcookie("user_id", "", time() - 3600, "/"); // Expire cookie
header("Location: login.php"); // Redirect to homepage
exit();
?>
