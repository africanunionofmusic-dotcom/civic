<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$user = $_SESSION['user'];

$conn->query("
UPDATE users
SET
    artist_agreement = 1,
    role = 'artist'
WHERE fullname = '$user'
");

header("Location: upload.php");
exit();
?>