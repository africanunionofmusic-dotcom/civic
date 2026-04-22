<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$song_id = $_GET['id'];

// fetch song from DB
$sql = "SELECT * FROM songs WHERE id = $song_id";
$result = $conn->query($sql);

$song = $result->fetch_assoc();

$title = $song['title'];
$artist = $song['artist'];
$price = $song['price'];
$cover = $song['cover'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - AUOM</title>
    <style>
        body {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
              url("images/music.jpg");
            color: white;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .checkout-box {
            background: #1e293b;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        a.pay-btn {
            display: inline-block;
            margin-top: 20px;
            background: #1db954;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
        }

        img {
            width: 200px;
            border-radius: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="checkout-box">
    <h2>Checkout</h2>

    <img src="<?php echo $cover; ?>">

    <p><strong>Item:</strong> <?php echo $title; ?></p>
    <p><strong>Artist:</strong> <?php echo $artist; ?></p>
    <p><strong>Price:</strong> $<?php echo $price; ?></p>

    <a href="payment_success.php?song_id=<?php echo $song_id; ?>" class="pay-btn">
        Proceed to Payment
    </a>
</div>

</body>
</html>