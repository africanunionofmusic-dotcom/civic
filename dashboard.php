<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "civic");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <li class="active"><a href="dashboard.php">🏠 Home</a></li>
    <li><a href="mymusic.php">🎵 My Music</a></li>
    <li><a href="upload.php">⬆ Upload</a></li>
    <li><a href="earnings.php">💰 Artist Dashboard</a></li>
    <li><a href="settings.php">⚙ Settings</a></li>
</ul>
    </div>

    <!-- Main -->
    <div class="main">

        <!-- Topbar -->
        <div class="topbar">
            <input type="text" placeholder="Search music...">

            <div class="profile">
                <span><?php echo $_SESSION['user']; ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Trending Music 🔥</h2>


<div class="music-grid">

<?php
$sql = "SELECT * FROM songs ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>

    <div class="music-card">
        <img src="<?php echo $row['cover']; ?>" class="cover">

        <p><?php echo $row['title']; ?></p>
        <span><?php echo $row['artist']; ?></span>

        <!-- AUDIO PREVIEW -->
        <audio controls>
            <source src="<?php echo $row['preview']; ?>" type="audio/mpeg">
        </audio>

        <a href="checkout.php?id=<?php echo $row['id']; ?>" class="buy-btn">Buy - <?php echo $row['price']; ?></a>
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

</div>

</body>
</html>