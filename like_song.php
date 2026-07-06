<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$song_id = $_GET['id'];
$type = $_GET['type'];

$check = $conn->query("
SELECT *
FROM song_likes
WHERE user='$user'
AND song_id='$song_id'
");

if($check->num_rows > 0){

    $conn->query("
    UPDATE song_likes
    SET type='$type'
    WHERE user='$user'
    AND song_id='$song_id'
    ");

}else{

    $conn->query("
    INSERT INTO song_likes(song_id,user,type)
    VALUES('$song_id','$user','$type')
    ");

}

header("Location: player.php?id=".$song_id);
exit();
?>