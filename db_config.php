<?php
$host = 'sql302.infinityfree.com'; // Example: sqlXXX.infinityfree.com
$dbname = 'if0_38635253_db_quikwork'; // Your database name from InfinityFree
$username = 'if0_38635253'; // Your InfinityFree database username
$password = 'Pifawiro24'; // Your InfinityFree database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo "Connected successfully!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
