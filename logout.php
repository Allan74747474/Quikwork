<?php
// logout.php
session_start();
session_destroy();
header("Location: login.php");
?>


<?php
// dashboard.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

echo "Welcome, " . $_SESSION['username'] . "!";
?>
