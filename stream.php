<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
session_start();
$conn = new mysqli("localhost", "root", "", "civic");

if(!isset($_SESSION['user'])){
    die("Access denied");
}

$user = $_SESSION['user'];
$song_id = $_GET['id'];

// check if user purchased
$check = $conn->query("
SELECT * FROM purchases 
WHERE user='$user' AND song_id=$song_id
");

if($check->num_rows == 0){
    die("You must purchase this song");
}

// get file path
$song = $conn->query("SELECT audio FROM songs WHERE id=$song_id")->fetch_assoc();
$file = $song['audio'];

if(!file_exists($file)){
    die("File not found");
}

// stream audio
header("Content-Type: audio/mpeg");
header("Content-Length: " . filesize($file));
readfile($file);
exit();
?>