<?php
// connect.php - connects to MySQL database

$host = "localhost";       // XAMPP localhost
$user = "root";            // default user
$password = "";            // default password
$db = "civic";              // database name we just created

$conn = new mysqli($host, $user, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>