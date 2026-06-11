<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location:index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$id = $_POST['id'];
$title = $_POST['title'];
$artist = $_POST['artist'];
$price = $_POST['price'];

$conn->query("
UPDATE songs
SET
title='$title',
artist='$artist',
price='$price'
WHERE id='$id'
");

header("Location: manage_songs.php");
exit();
?>