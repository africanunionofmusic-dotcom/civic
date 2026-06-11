<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$sales = $conn->query("
SELECT
    purchases.user,
    songs.title,
    songs.price
FROM purchases
JOIN songs ON purchases.song_id = songs.id
WHERE songs.user='$user'
ORDER BY purchases.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Artist Sales</title>
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

        <h1>🛒 Song Sales</h1>

        <?php if($sales->num_rows > 0){ ?>

        <table class="sales-table">

            <tr>
                <th>Buyer</th>
                <th>Song</th>
                <th>Amount</th>
            </tr>

            <?php while($sale = $sales->fetch_assoc()){ ?>

            <tr>
                <td><?php echo $sale['user']; ?></td>
                <td><?php echo $sale['title']; ?></td>
                <td>$<?php echo $sale['price']; ?></td>
            </tr>

            <?php } ?>

        </table>

        <?php } else { ?>

            <p>No sales yet.</p>

        <?php } ?>

    </div>

</div>

</body>
</html>