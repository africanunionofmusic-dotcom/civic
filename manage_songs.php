<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$songs = $conn->query("
SELECT *
FROM songs
WHERE user='$user'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Songs</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="admin-container">

    <div class="admin-sidebar">

        <h2>🎧 AUOM</h2>

        <ul>
            <li><a href="dashboard.php">🏠 Home</a></li>
            <li><a href="artist_dashboard.php">📊 Artist Dashboard</a></li>
            <li><a href="manage_songs.php">🎼 Manage Songs</a></li>
            <li><a href="artist_sales.php">🛒 Sales</a></li>
            <li><a href="earnings.php">💰 Earnings</a></li>
            <li><a href="withdrawals.php">🏦 Withdrawals</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>

    </div>

    <div class="admin-main">

        <h1>🎼 Manage Songs</h1>

        <div class="music-grid">

        <?php
        if($songs->num_rows > 0){

            while($song = $songs->fetch_assoc()){
        ?>

            <div class="music-card">

                <img src="<?php echo $song['cover']; ?>">

                <p><?php echo $song['title']; ?></p>

                <span>
                    <?php echo $song['artist']; ?>
                </span>

                <br><br>

                <span>
                    Price: $<?php echo $song['price']; ?>
                </span>

                <br><br>

                <a href="edit_song.php?id=<?php echo $song['id']; ?>"
                   class="buy-btn">
                   <button>Edit Song</button>
                </a>

                <a href="delete_my_song.php?id=<?php echo $song['id']; ?>"
   class="buy-btn"
   style="background:red;"
   onclick="return confirm('Are you sure you want to permanently delete this song?');">
   <button>Delete Song</button>
</a>
            </div>

        <?php
            }
        } else {
            echo "<p>No songs uploaded yet.</p>";
        }
        ?>

        </div>

    </div>

</div>

</body>
</html>