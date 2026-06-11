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
            background:#0f172a;
            color:white;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .player{
            width:400px;
            background:#1e293b;
            padding:25px;
            border-radius:20px;
            text-align:center;
            box-shadow:0 10px 30px rgba(0,0,0,0.5);
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
            justify-content:space-between;
        }

        .controls button{
    text-decoration:none;
    padding:10px;
    background:#334155;
    color:white;
    border:none;
    border-radius:10px;
    flex:1;
    margin:5px;
    cursor:pointer;
    font-size:14px;
}

        .controls a{
            text-decoration:none;
            padding:10px;
            background:#334155;
            color:white;
            border-radius:10px;
            flex:1;
            margin:5px;
        }

        .ai{
            background:#1db954 !important;
        }
    </style>
</head>

<body>

<div class="player">
    <?php if($mode == "ai_dj"){ ?>

<h3>🧠 AUOM AI DJ MODE</h3>
<p>Preparing your next vibe...</p>

<?php } ?>
    <img src="<?php echo $cover; ?>" class="cover" id="coverImage">

        <div class="title" id="songTitle"><?php echo $title; ?></div>

        <div class="artist" id="artistName">by <?php echo $artist; ?></div>
        
    <!-- AUDIO -->
    <audio id="audioPlayer" controls autoplay>
    <source src="<?php echo $audio; ?>" type="audio/mpeg">
</audio>


   <div class="controls">

    <button id="prevBtn">⏮ Previous</button>

    <button id="nextBtn">⏭ Next</button>

    <a href="dashboard.php">🏠 Home</a>

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
}

// NEXT BUTTON
document.getElementById("nextBtn")
.addEventListener("click", function(){

    if(index + 1 < queue.length){

        playSong(index + 1);

    }

});

// PREVIOUS BUTTON
document.getElementById("prevBtn")
.addEventListener("click", function(){

    if(index - 1 >= 0){

        playSong(index - 1);

    }

});

// AUTO NEXT
audio.addEventListener("ended", function(){

    if(mode === "ai_dj"){

        if(index + 1 < queue.length){
            playSong(index + 1);
        }

    }

});
</script>

</body>
</html>