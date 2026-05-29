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

$song_id = $_GET['song_id'];
$method = $_GET['method'];

// FETCH SONG
$song = $conn->query("SELECT * FROM songs WHERE id='$song_id'");
$data = $song->fetch_assoc();

$title = $data['title'];
$artist = $data['artist'];
$cover = $data['cover'];
$price = $data['price'];

if(isset($_POST['submit'])){

    $method = $_POST['method'];
    $song_id = $_POST['song_id'];

    // IMAGE
    $proof = $_FILES['proof'];

    $proofName = time() . "_" . $proof['name'];

    $proofPath = "uploads/proofs/" . $proofName;

    move_uploaded_file($proof['tmp_name'], $proofPath);

    // SAVE PAYMENT
    $conn->query("
    INSERT INTO payments(user, song_id, method, proof, status)
    VALUES('$user','$song_id','$method','$proofPath','pending')
    ");

    // WHATSAPP ALERT
    $message = urlencode(
        "🚨 NEW AUOM PAYMENT\n\n".
        "User: $user\n".
        "Song: $title\n".
        "Artist: $artist\n".
        "Method: $method\n\n".
        "Please login to approve."
    );

    file_get_contents(
        "https://api.callmebot.com/whatsapp.php?phone=447878688244&text=$message&apikey=2171303"
    );

    header("Location: payment_pending.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Payment Proof</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;

            background-image:
            linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)),
            url("images/music.jpg");

            background-size:cover;
            color:white;
        }

        .box{
            width:430px;
            background:#1e293b;
            padding:30px;
            border-radius:20px;
            text-align:center;

            box-shadow:0 10px 30px rgba(0,0,0,0.4);
        }

        .box img{
            width:180px;
            height:180px;
            object-fit:cover;
            border-radius:15px;
            margin-bottom:20px;
        }

        h1{
            margin-bottom:10px;
        }

        .method{
            color:#1db954;
            margin-bottom:20px;
        }

        input[type="file"]{
            width:100%;
            padding:12px;
            margin-top:20px;
            background:#0f172a;
            border:none;
            border-radius:10px;
            color:white;
        }

        button{
            width:100%;
            padding:14px;
            margin-top:20px;

            border:none;
            border-radius:10px;

            background:#1db954;
            color:white;

            font-size:16px;
            font-weight:bold;

            cursor:pointer;
            transition:0.3s;
        }

        button:hover{
            background:#17a44a;
        }

    </style>
</head>

<body>

<div class="box">

    <img src="<?php echo $cover; ?>">

    <h1><?php echo $title; ?></h1>

    <p>by <?php echo $artist; ?></p>

    <h3 class="method">
        Payment Method: <?php echo strtoupper($method); ?>
    </h3>

    <p>
        Upload screenshot or proof of payment.
    </p>

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">

        <input type="hidden" name="method" value="<?php echo $method; ?>">

        <input type="file" name="proof" required>

        <button type="submit" name="submit">
            Submit Proof
        </button>

    </form>

</div>

</body>
</html>