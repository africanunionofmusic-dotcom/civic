<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$user = $_SESSION['user'];

$sql = "
SELECT
songs.id,
songs.title,
songs.cover,
songs.price,
COUNT(purchases.id) as total_sales,
(COUNT(purchases.id) * songs.price) as gross_sales
FROM songs
LEFT JOIN purchases ON songs.id = purchases.song_id
WHERE songs.user='$user'
GROUP BY songs.id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM Earnings</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="content">

    <h2>💰 Earnings Dashboard</h2>

    <div class="library-grid">

<?php

$total_gross = 0;
$total_artist = 0;
$total_auom = 0;

while($row = $result->fetch_assoc()){

    $gross = $row['gross_sales'];

    $artist_share = $gross * 0.80;
    $auom_share = $gross * 0.20;

    $total_gross += $gross;
    $total_artist += $artist_share;
    $total_auom += $auom_share;
?>

    <div class="library-card">

        <img src="<?php echo $row['cover']; ?>">

        <p><?php echo $row['title']; ?></p>

        <span>Sales: <?php echo $row['total_sales']; ?></span><br>

        <span>
            Gross Revenue:
            $<?php echo number_format($gross,2); ?>
        </span>
        <br>

        <span>
            Your Share (80%):
            $<?php echo number_format($artist_share,2); ?>
        </span>

    </div>

<?php } ?>

    </div>

    <div style="
        margin-top:30px;
        background:#1e293b;
        padding:25px;
        border-radius:15px;
        max-width:500px;
    ">

        <h3>📊 Revenue Summary</h3>

        <p>
            Total Revenue Generated:
            <strong>$<?php echo number_format($total_gross,2); ?></strong>
        </p>

        <p>
            Your Earnings (80%):
            <strong>$<?php echo number_format($total_artist,2); ?></strong>
        </p>

        <p>
            AUOM Share (20%):
            <strong>$<?php echo number_format($total_auom,2); ?></strong>
        </p>

    </div>

</div>

</body>
</html>