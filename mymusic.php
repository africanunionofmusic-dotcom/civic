<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$user = $_SESSION['user'];

$sql = "
SELECT songs.*
FROM songs
JOIN purchases ON songs.id = purchases.song_id
WHERE purchases.user = '$user'
ORDER BY purchases.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Music Library</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body oncontextmenu="return false;">
    <div class="content">

<h2>🎧 My Music Library</h2>

<div style="text-align:center; margin-bottom:20px;">

    <a href="player.php?mode=ai_dj"
       style="
            background:#1db954;
            padding:12px 20px;
            border-radius:10px;
            color:white;
            text-decoration:none;
            font-weight:bold;
            display:inline-block;
       ">

        🧠 AUOM A.I DJ

    </a>

</div>

<div class="music-grid">

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>

    <div class="music-card">
        <div class="cover-wrapper">

    <a href="player.php?id=<?php echo $row['id']; ?>">

        <img src="<?php echo $row['cover']; ?>" class="cover">

    </a>

</div>

        <p>

              <a
                   href="player.php?id=<?php echo $row['id']; ?>"
                       style="color:white;text-decoration:none;">

                          <?php echo $row['title']; ?>

                </a>

           </p>
        <span><?php echo $row['artist']; ?></span>
        <?php

$likes = $conn->query("
SELECT COUNT(*) AS total
FROM song_likes
WHERE song_id='{$row['id']}'
AND type='Like'
")->fetch_assoc()['total'];

$comments = $conn->query("
SELECT COUNT(*) AS total
FROM song_comments
WHERE song_id='{$row['id']}'
")->fetch_assoc()['total'];

?>

<div class="song-engagement">

    <a href="#"
       class="likeBtn"
       data-song="<?php echo $row['id']; ?>">

        ❤️

        <span id="likes-<?php echo $row['id']; ?>">

            <?php echo $likes; ?>

        </span>

    </a>

    &nbsp;&nbsp;&nbsp;

    <a href="#"
       class="comment-link"
       onclick="openComments(<?php echo $row['id']; ?>);return false;">

        💬 <?php echo $comments; ?>

    </a>

</div>

        <!-- FULL SONG ACCESS -->
        <audio controls controlsList="nodownload">
            <source src="stream.php?id=<?php echo $row['id']; ?>" type="audio/mpeg">
        </audio>
    </div>

<?php
    }
} else {
    echo "<p>No purchased music yet.</p>";
}
?>

</div>

</div>
<div id="commentsOverlay" class="comments-overlay">

    <div class="comments-window">

        <span id="closeComments" onclick="closeComments()">&times;</span>

        <div id="commentsContent">

            Loading...

        </div>

    </div>

</div>

<script>

// =======================
// AJAX LIKE
// =======================

document.querySelectorAll(".likeBtn").forEach(button=>{

button.addEventListener("click",function(e){

e.preventDefault();

let song=this.dataset.song;

let form=new FormData();

form.append("song_id",song);

fetch("ajax/like_song.php",{

method:"POST",

body:form

})

.then(response=>response.text())

.then(total=>{

document.getElementById("likes-"+song).innerHTML=total;

});

});

});


// =======================
// COMMENTS POPUP
// =======================

function openComments(songId){

document.getElementById("commentsOverlay").style.display="flex";

fetch("comments_modal.php?song_id="+songId)

.then(response=>response.text())

.then(data=>{

document.getElementById("commentsContent").innerHTML=data;

});

}

function closeComments(){

document.getElementById("commentsOverlay").style.display="none";

}

document.getElementById("commentsOverlay").onclick=function(e){

if(e.target===this){

closeComments();

}

}

document.addEventListener("keydown",function(e){

if(e.key==="Escape"){

closeComments();

}

});

</script>
</body>
</html>