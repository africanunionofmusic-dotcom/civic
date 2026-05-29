<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

if($_SESSION['user'] != 'admin'){
    echo "Access denied";
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

// FETCH PURCHASES
$purchases = $conn->query("
SELECT purchases.*, songs.title, songs.artist, songs.cover
FROM purchases
JOIN songs ON purchases.song_id = songs.id
ORDER BY purchases.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM Purchases</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>

<body>

<div class="admin-container">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">

        <h2>AUOM ADMIN</h2>

        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="admin_users.php">Users</a></li>
            <li><a href="admin_songs.php">Songs</a></li>
            <li><a href="admin_purchases.php">Purchases</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- MAIN -->
    <div class="admin-main">

        <h1>💳 All Purchases</h1>

        <?php
        if($purchases->num_rows > 0){

            while($row = $purchases->fetch_assoc()){
        ?>

        <div class="payment-card">

            <div class="payment-info">

                <img src="<?php echo $row['cover']; ?>" class="proof-image">

                <div class="payment-details">

                    <h3><?php echo $row['title']; ?></h3>

                    <p>
                        Artist: <?php echo $row['artist']; ?>
                    </p>

                    <p>
                        Purchased By: <?php echo $row['user']; ?>
                    </p>

                </div>

            </div>

        </div>

        <?php
            }

        } else {

            echo "<p>No purchases yet.</p>";

        }
        ?>

    </div>

</div>

</body>
</html>