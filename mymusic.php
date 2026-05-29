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

<div class="music-grid">

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>

    <div class="music-card">
        <div class="cover-wrapper">
                     <img src="<?php echo $row['cover']; ?>" class="cover">
        </div>

        <p><?php echo $row['title']; ?></p>
        <span><?php echo $row['artist']; ?></span>

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

</body>
</html>