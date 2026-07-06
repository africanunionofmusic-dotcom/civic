<?php
session_start();

$conn = new mysqli("localhost","root","","civic");

if(!isset($_SESSION['user'])){
    exit();
}

$user = $_SESSION['user'];

$song_id = $_POST['song_id'];

$type = "Like";

/*
CHECK IF USER ALREADY LIKED
*/

$check = $conn->query("
SELECT *
FROM song_likes
WHERE user='$user'
AND song_id='$song_id'
");

if($check->num_rows){

    $row = $check->fetch_assoc();

    if($row['type']=="Like"){

        $conn->query("
        DELETE
        FROM song_likes
        WHERE id='{$row['id']}'
        ");

    }else{

        $conn->query("
        UPDATE song_likes
        SET type='Like'
        WHERE id='{$row['id']}'
        ");

    }

}else{

    $conn->query("
    INSERT INTO song_likes(song_id,user,type)

    VALUES('$song_id','$user','Like')
    ");

}

$total = $conn->query("
SELECT COUNT(*) total
FROM song_likes
WHERE song_id='$song_id'
AND type='Like'
")->fetch_assoc()['total'];

echo $total;