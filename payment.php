<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$song_id = $_GET['song_id'];

$song = $conn->query("SELECT * FROM songs WHERE id = $song_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="upload-container">
<div class="upload-box">

<h2>💳 Make Payment</h2>

<img src="<?php echo $song['cover']; ?>" style="width:100%; border-radius:10px;">

<p><strong><?php echo $song['title']; ?></strong></p>
<p><?php echo $song['artist']; ?></p>
<p>Price: $<?php echo $song['price']; ?></p>

<br>

<!-- YOUR SILICON PAYMENT LINK -->
<a href="https://silicon-pay.com/payModal/S653a0f5e76c399.61808802" target="_blank" class="buy-btn">
    Pay Now
</a>

<br><br>

<!-- AFTER PAYMENT -->
<form action="submit_payment.php" method="POST">

    <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">

    <input type="text" name="reference" placeholder="Enter Payment Reference" required>

    <button type="submit">Submit Payment</button>

</form>

</div>
</div>

</body>
</html>