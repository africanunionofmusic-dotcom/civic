<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$id = $_GET['id'];

$song = $conn->query("SELECT * FROM songs WHERE id='$id'");

if($song->num_rows == 0){
    die("Song not found");
}

$data = $song->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Song</title>
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

        <h1>✏️ Edit Song</h1>

        <form action="save_song.php" method="POST">

            <input type="hidden"
                   name="id"
                   value="<?php echo $data['id']; ?>">

            <p>Song Title</p>

            <input type="text"
                   name="title"
                   value="<?php echo $data['title']; ?>"
                   required>

            <br><br>

            <p>Artist Name</p>

            <input type="text"
                   name="artist"
                   value="<?php echo $data['artist']; ?>"
                   required>

            <br><br>

            <p>Price</p>

            <input type="number"
                   name="price"
                   value="<?php echo $data['price']; ?>"
                   required>

            <br><br>

            <button type="submit" class="approve-btn">
                Save Changes
            </button>

        </form>

    </div>

</div>

</body>
</html>