<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location:index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];
$id = $_GET['id'];

// verify ownership
$check = $conn->query("
SELECT *
FROM songs
WHERE id='$id'
AND user='$user'
");

if($check->num_rows == 0){
    die("Access denied");
}

// delete song
$conn->query("
DELETE FROM songs
WHERE id='$id'
");

// remove purchases too
$conn->query("
DELETE FROM purchases
WHERE song_id='$id'
");

header("Location: manage_songs.php");
exit();
?>