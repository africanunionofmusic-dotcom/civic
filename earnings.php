<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$user = $_SESSION['user'];

// fetch songs + sales
$sql = "
SELECT songs.id, songs.title, songs.cover, songs.price,
COUNT(purchases.id) as total_sales,
(COUNT(purchases.id) * songs.price) as earnings
FROM songs
LEFT JOIN purchases ON songs.id = purchases.song_id
WHERE songs.user = '$user'
GROUP BY songs.id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Earnings Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="content">

<h2>💰 Your Earnings</h2>

<div class="library-grid">

<?php
$total_earnings = 0;

while($row = $result->fetch_assoc()){
    $total_earnings += $row['earnings'];
?>

<div class="library-card">
    <img src="<?php echo $row['cover']; ?>">

    <p><?php echo $row['title']; ?></p>

    <span>Sales: <?php echo $row['total_sales']; ?></span><br>
    <span>Earnings: $<?php echo $row['earnings']; ?></span>
</div>

<?php } ?>

</div>

<h3 style="margin-top:20px;">Total Earnings: $<?php echo $total_earnings; ?></h3>

</div>

</body>
</html>