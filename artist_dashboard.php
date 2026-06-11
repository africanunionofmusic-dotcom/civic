<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

// TOTAL SONGS
$songs = $conn->query("
SELECT COUNT(*) as total
FROM songs
WHERE user='$user'
");

$total_songs = $songs->fetch_assoc()['total'];


// TOTAL SALES
$sales = $conn->query("
SELECT COUNT(*) as total
FROM purchases
JOIN songs ON purchases.song_id = songs.id
WHERE songs.user='$user'
");

$total_sales = $sales->fetch_assoc()['total'];


// TOTAL EARNINGS
$earnings = $conn->query("
SELECT SUM(songs.price) as total
FROM purchases
JOIN songs ON purchases.song_id = songs.id
WHERE songs.user='$user'
");

$total_earnings = $earnings->fetch_assoc()['total'];

if(!$total_earnings){
    $total_earnings = 0;
}


// LATEST SONG
$latest = $conn->query("
SELECT title
FROM songs
WHERE user='$user'
ORDER BY id DESC
LIMIT 1
");

$latest_song = "No uploads yet";

if($latest->num_rows > 0){
    $latest_song = $latest->fetch_assoc()['title'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artist Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

<div class="admin-container">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">

        <h2>🎧 AUOM ARTIST</h2>

        <ul>
            <li><a href="dashboard.php">🏠 Home</a></li>
            <li><a href="upload.php">⬆ Upload</a></li>
            <li><a href="manage_songs.php">🎼 Manage Songs</a></li>
            <li><a href="artist_sales.php">🛒 Sales</a></li>
            <li><a href="earnings.php">💰 Earnings</a></li>
            <li><a href="withdrawals.php">🏦 Withdrawals</a></li>
            <li><a href="logout.php">🚪 Logout</a></li>
        </ul>

    </div>

    <!-- MAIN -->
    <div class="admin-main">

        <h1>📊 Artist Dashboard</h1>

        <div class="stats-grid">

            <div class="stat-card">
                <h3>Total Songs</h3>
                <p><?php echo $total_songs; ?></p>
            </div>

            <div class="stat-card">
                <h3>Total Sales</h3>
                <p><?php echo $total_sales; ?></p>
            </div>

            <div class="stat-card revenue-card">
                <h3>Total Earnings</h3>
                <p>$<?php echo $total_earnings; ?></p>
            </div>

            <div class="stat-card">
                <h3>Latest Upload</h3>
                <p style="font-size:18px;">
                    <?php echo $latest_song; ?>
                </p>
            </div>

        </div>

        <h2 style="margin-bottom:20px;">
            Quick Actions
        </h2>

        <div class="stats-grid">

            <a href="upload.php" class="stat-card">
                <h3>⬆ Upload New Song</h3>
            </a>

            <a href="manage_songs.php" class="stat-card">
                <h3>🎼 Manage Songs</h3>
            </a>

            <a href="artist_sales.php" class="stat-card">
                <h3>🛒 View Sales</h3>
            </a>

            <a href="earnings.php" class="stat-card">
                <h3>💰 Earnings</h3>
            </a>

        </div>

    </div>

</div>

</body>
</html>