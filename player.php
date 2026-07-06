<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$mode = $_GET['mode'] ?? null;
$id = $_GET['id'] ?? null;

/*
-----------------------------------
🧠 AI DJ MODE
-----------------------------------
*/
if($mode == "ai_dj"){

    $songs = [];

    $sql = "
    SELECT songs.*
    FROM songs
    JOIN purchases ON songs.id = purchases.song_id
    WHERE purchases.user = '$user'
    ";

    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()){
        $songs[] = $row;
    }

    shuffle($songs);

    $song = $songs[0];
    $queue = json_encode($songs);

}else{

    /*
    -----------------------------------
    🎵 NORMAL MODE
    -----------------------------------
    */

    $song = $conn->query("SELECT * FROM songs WHERE id='$id'")->fetch_assoc();

    if(!$song){
        die("Song not found");
    }

    $queue = "[]";
}

$title = $song['title'];
$artist = $song['artist'];
$cover = $song['cover'];
$audio = $song['audio'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM A.I DJ</title>

    <style>
       body{
    margin:0;
    font-family:Arial;
     background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
    url("images/music.jpg");

    color:white;

    display:flex;
    justify-content:center;

    min-height:100vh;

    padding:30px 0;

    box-sizing:border-box;
}

        .player{
            width:500px;
            max-width: 500px;
            background:#1e293b;
            padding:25px;
            border-radius:20px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,0.5);
              margin:auto;
        }

        .player img{
            width:100%;
            height:260px;
            object-fit:cover;
            border-radius:15px;
            
        }

        .title{
            font-size:20px;
            margin-top:15px;
            font-weight:bold;
        }

        .artist{
            color:#94a3b8;
            margin-bottom:15px;
        }

        audio{
            width:100%;
            margin-top:10px;
        }

        .controls{
    margin-top:15px;
    display:flex;
    gap:10px;
}

.controls button,
.controls a{

    flex:1;

    height:45px;

    display:flex;
    justify-content:center;
    align-items:center;

    text-decoration:none;

    background:#334155;

    color:white;

    border:none;

    border-radius:10px;

    cursor:pointer;

    font-size:14px;
}

        .ai{
            background:#1db954 !important;
        }

        .up-next{
    margin-top:20px;
    background:#0f172a;
    padding:15px;
    border-radius:12px;
    text-align:left;
    width:100%;
    box-sizing:border-box;
}

.up-next h3{
    color:#1db954;
    margin-bottom:10px;
    text-align:center;
}

.up-next p{
    margin:8px 0;
    color:white;
}

.now-playing{

    background:#0f172a;

    padding:15px;

    border-radius:15px;

    margin-top:15px;
    margin-bottom:15px;

    text-align:center;

    border:1px solid rgba(29,185,84,0.25);

}

.now-label{

    color:#1db954;

    font-size:12px;

    font-weight:bold;

    letter-spacing:2px;

    margin-bottom:8px;

}

.title{

    font-size:24px;

    font-weight:bold;

    margin-bottom:5px;

}

.artist{

    color:#94a3b8;

    font-size:15px;

}


.queue-song{

    display:flex;

    align-items:center;

    gap:12px;

    padding:10px;

    border-radius:10px;

    background:#111827;

    margin-bottom:10px;

}

.queue-number{

    width:35px;
    height:35px;

    display:flex;

    align-items:center;
    justify-content:center;

    background:#1db954;

    color:white;

    border-radius:50%;

    font-weight:bold;

}

.queue-title{

    font-weight:bold;

    color:white;

}

.queue-artist{

    color:#94a3b8;

    font-size:13px;

}

.ai-header{
    margin-bottom:20px;
}

.ai-header h3{
    color:#1db954;
    margin-bottom:5px;
}

.ai-header p{
    color:#94a3b8;
    margin-top:0;
}

.song-actions{
    display:flex;
    gap:10px;
    margin-top:15px;
}

.song-actions a{
    flex:1;
    padding:12px;
    text-decoration:none;
    text-align:center;
    background:#334155;
    color:white;
    border-radius:10px;
    font-weight:bold;
}

.song-stats{
    margin-top:15px;
    margin-bottom:10px;
    color:#cbd5e1;
    font-size:16px;
    font-weight:bold;
}

.comment-box{
    margin-top:20px;
}

.comment-box textarea{

    width:100%;
    height:90px;

    background:#0f172a;

    border:1px solid #334155;

    color:white;

    border-radius:10px;

    padding:12px;

    resize:none;

    box-sizing:border-box;
}

.comment-box button{

    width:100%;

    margin-top:10px;

    padding:12px;

    border:none;

    border-radius:10px;

    background:#1db954;

    color:white;

    font-weight:bold;

    cursor:pointer;
}

.comments-section{

    margin-top:25px;

    text-align:left;
}

.comments-section h3{

    color:#1db954;

    margin-bottom:15px;
}

.comment{

    background:#0f172a;

    padding:12px;

    border-radius:10px;

    margin-bottom:10px;
}

.comment strong{

    color:#1db954;
}

.comment p{

    margin-top:8px;

    color:white;
}


    </style>
</head>

<body>

<div class="player">
    <?php if($mode == "ai_dj"){ ?>

<div class="ai-header">

    <h3>🧠 AUOM A.I DJ MODE</h3>

    <p>Bringing you the vibe...</p>

</div>

<?php } ?>
    <img src="<?php echo $cover; ?>" class="cover" id="coverImage">


    <div class="now-playing">

    <div class="now-label">
        🎧 NOW PLAYING
    </div>

    <div class="title" id="songTitle">
        <?php echo $title; ?>
    </div>

    <div class="artist" id="artistName">
        <?php echo $artist; ?>
    </div>

</div>


    <!-- AUDIO -->
    <audio id="audioPlayer" controls autoplay>
    <source src="<?php echo $audio; ?>" type="audio/mpeg">
</audio>

<?php

$likes = $conn->query("
SELECT COUNT(*) AS total
FROM song_likes
WHERE song_id='{$song['id']}'
AND type='Like'
")->fetch_assoc()['total'];

$dislikes = $conn->query("
SELECT COUNT(*) AS total
FROM song_likes
WHERE song_id='{$song['id']}'
AND type='Dislike'
")->fetch_assoc()['total'];

$comments = $conn->query("
SELECT COUNT(*) AS total
FROM song_comments
WHERE song_id='{$song['id']}'
")->fetch_assoc()['total'];

?>

<div class="song-stats">

<a href="like_song.php?id=<?php echo $song['id']; ?>&type=Like"
class="reaction-btn">

❤️ <span><?php echo $likes; ?></span>

</a>

<a href="like_song.php?id=<?php echo $song['id']; ?>&type=Dislike"
class="reaction-btn">

👎 <span><?php echo $dislikes; ?></span>

</a>

<a href="#comments"
class="reaction-btn">

💬 <span><?php echo $comments; ?></span>

</a>

</div>
    

    <div class="comment-box">

<form method="POST" action="comment.php">

    <input
        type="hidden"
        name="song_id"
        value="<?php echo $song['id']; ?>">

    <textarea
        name="comment"
        placeholder="Share your thoughts about this Auom A.I DJ..."
        required></textarea>

    <button type="submit">
        💬 Post Comment
    </button>

</form>

<div class="comments-section">

<h3 id="comments">💬 Comments</h3>

<?php

$comments = $conn->query("
SELECT *
FROM song_comments
WHERE song_id='{$song['id']}'
ORDER BY id DESC
");

if($comments->num_rows > 0){

    while($comment = $comments->fetch_assoc()){

?>

<div class="comment">

    <strong>
        <?php echo $comment['user']; ?>
    </strong>

    <p>
        <?php echo $comment['comment']; ?>
    </p>

</div>

<?php

    }

}else{

    echo "<p>No comments yet.</p>";

}

?>

</div>

</div>

</div>


   <div class="controls">

    <button id="prevBtn">⏮</button>

    <button id="nextBtn">⏭</button>

    <a href="dashboard.php">🏠</a>

</div>
    <div class="up-next">

    <h3>🧠 AUOM A.I DJ Queue</h3>

    <div id="queueList"></div>



</div>

</div>
<script>
let mode = "<?php echo $mode; ?>";
let queue = <?php echo $queue; ?>;
let index = 0;

const audio = document.getElementById("audioPlayer");

// NEXT SONG FUNCTION
function playSong(i){
    if(i >= queue.length) return;

    let song = queue[i];

    document.getElementById("songTitle").innerText =
song.title;

document.getElementById("artistName").innerText =
"by " + song.artist;

document.getElementById("coverImage").src =
song.cover;
    audio.src = "stream.php?id=" + song.id;
    audio.play();

    index = i;
    updateQueue();
}

// NEXT BUTTON
document.getElementById("nextBtn")
.addEventListener("click", function(){

    let nextIndex = index + 1;

    if(nextIndex >= queue.length){
        nextIndex = 0;
    }

    playSong(nextIndex);

});

// PREVIOUS BUTTON
document.getElementById("prevBtn")
.addEventListener("click", function(){

    let prevIndex = index - 1;

    if(prevIndex < 0){
        prevIndex = queue.length - 1;
    }

    playSong(prevIndex);

});
// AUTO NEXT
audio.addEventListener("ended", function(){

    if(mode === "ai_dj"){

        let nextIndex = index + 1;

        if(nextIndex >= queue.length){
            nextIndex = 0;
        }

        playSong(nextIndex);

    }

});

function updateQueue(){

    if(queue.length <= 1){
        document.getElementById("queueList").innerHTML =
        "<p>No upcoming songs.</p>";
        return;
    }

    let html = "";

    for(let i = 1; i <= 2; i++){

        let nextIndex = (index + i) % queue.length;

    html += `

<div class="queue-song">

    <div class="queue-number">
        ${i}
    </div>

    <div class="queue-info">

        <div class="queue-title">
            ${queue[nextIndex].title}
        </div>

        <div class="queue-artist">
            ${queue[nextIndex].artist}
        </div>

    </div>

</div>

`;    
    
    }

    document.getElementById("queueList").innerHTML = html;
}

updateQueue();
</script>

</body>
</html>