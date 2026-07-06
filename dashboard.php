<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

if($conn->connect_error){
    die("Connection failed: ".$conn->connect_error);
}

$user = $_SESSION['user'];

$role_query = $conn->query("
SELECT role
FROM users
WHERE fullname='$user'
");

$role_data = $role_query->fetch_assoc();
$role = $role_data['role'];
?>

<!DOCTYPE html>
<html>
<head>

<title>AUOM Dashboard</title>

<link rel="stylesheet" href="dashboard.css">

</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->

    <div class="sidebar">

        <h2>🎧 AUOM</h2>

        <ul>

            <li class="active">
                <a href="dashboard.php">🏠 Home</a>
            </li>

            <li>
                <a href="mymusic.php">🎵 My Music</a>
            </li>

            <?php if($role=="fan"){ ?>

            <li>
                <a href="become_artist.php">🎤 AUOM For Artists</a>
            </li>

            <?php } ?>

            <?php if($role=="artist" || $role=="admin"){ ?>

            <li>
                <a href="artist_dashboard.php">📊 AUOM For Artists</a>
            </li>

            <?php } ?>

            <li>
                <a href="settings.php">⚙ Settings</a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->

    <div class="main">

        <div class="topbar">

            <input type="text" placeholder="Search music...">

            <div class="profile">

                <span><?php echo $_SESSION['user']; ?></span>

                <a href="logout.php">Logout</a>

            </div>

        </div>

        <div class="content">

            <h2>Trending Music 🔥</h2>

            <div class="music-grid">

<?php

$sql="SELECT * FROM songs ORDER BY id DESC";

$result=$conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()){

$likes=$conn->query("
SELECT COUNT(*) total
FROM song_likes
WHERE song_id='{$row['id']}'
AND type='Like'
")->fetch_assoc()['total'];

$comments=$conn->query("
SELECT COUNT(*) total
FROM song_comments
WHERE song_id='{$row['id']}'
")->fetch_assoc()['total'];

?>

<div class="music-card">

    <img src="<?php echo $row['cover']; ?>" class="cover">

    <p><?php echo $row['title']; ?></p>

    <span><?php echo $row['artist']; ?></span>

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

    <audio controls controlsList="nodownload">

        <source src="<?php echo $row['preview']; ?>" type="audio/mpeg">

    </audio>

    <a href="checkout.php?id=<?php echo $row['id']; ?>" class="buy-btn">

        Buy - $<?php echo number_format($row['price'],2); ?>

    </a>

</div>

<?php

}

}else{

echo "<p>No songs uploaded yet.</p>";

}

?>

            </div>

        </div>

    </div>

</div>
<script>

// ===============================
// AJAX LIKE
// ===============================

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


// ===============================
// COMMENTS POPUP
// ===============================

function openComments(songId){

document.getElementById("commentsOverlay").style.display="flex";

fetch("comments_modal.php?song_id="+songId)

.then(response=>response.text())

.then(data=>{

document.getElementById("commentsContent").innerHTML=data;

});

}


// Close using X

function closeComments(){

document.getElementById("commentsOverlay").style.display="none";

}

// Close when clicking outside

document.getElementById("commentsOverlay").onclick=function(e){

if(e.target==this){

closeComments();

}

}
</script>

<div id="commentsOverlay" class="comments-overlay">

    <div class="comments-window">

        
    <span id="closeComments" onclick="closeComments()">&times;</span>

        <div id="commentsContent">

        Loading...

        </div>

    </div>

</div>
</body>

</html>