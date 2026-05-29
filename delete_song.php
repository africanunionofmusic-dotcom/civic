<?php
session_start();

if($_SESSION['user'] != 'admin'){
    die("Access denied");
}

$conn = new mysqli("localhost","root","","civic");

$id = $_GET['id'];

// delete song
$conn->query("DELETE FROM songs WHERE id=$id");

// also delete related purchases (important)
$conn->query("DELETE FROM purchases WHERE song_id=$id");

header("Location: admin_songs.php");
exit();
?>