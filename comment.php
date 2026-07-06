<?php

session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$song_id = $_POST['song_id'];
$comment = trim($_POST['comment']);

if($comment != ""){

    $comment = $conn->real_escape_string($comment);

    $conn->query("
    INSERT INTO song_comments(song_id,user,comment)
    VALUES('$song_id','$user','$comment')
    ");

}

header("Location: player.php?id=".$song_id);
exit();

?>