<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$user = $_SESSION['user'];
$song_id = $_POST['song_id'];
$reference = $_POST['reference'];

// Save payment as pending
$conn->query("INSERT INTO payments (user, song_id, reference)
VALUES ('$user', '$song_id', '$reference')");

// Fetch song details
$song = $conn->query("SELECT * FROM songs WHERE id='$song_id'");
$data = $song->fetch_assoc();

$title = $data['title'];
$artist = $data['artist'];
$cover = $data['cover'];
$price = $data['price'];

/* ================================
   WHATSAPP NOTIFICATION (CALLMEBOT)
==================================*/

// YOUR DATA
$phone = "447878688244";
$apikey = "2171303";

// Build clean message first
$message = "AUOM PAYMENT ALERT\n\n";
$message .= "User: $user\n";
$message .= "Song: $title\n";
$message .= "Artist: $artist\n";
$message .= "Price: $$price\n";
$message .= "Reference: $reference\n\n";
$message .= "Please login and approve/reject in admin dashboard.";

// PROPER encoding (VERY IMPORTANT)
$encodedMessage = urlencode($message);

// API URL
$url = "https://api.callmebot.com/whatsapp.php?phone=$phone&text=$encodedMessage&apikey=$apikey";

// Send request
file_get_contents($url);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Submitted - AUOM</title>

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
            linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
            url("images/music.jpg");

            background-size:cover;
            background-position:center;

            color:white;
        }

        .payment-box{
            width:430px;
            background:#1e293b;
            padding:35px;
            border-radius:20px;
            text-align:center;
            box-shadow:0 10px 40px rgba(0,0,0,0.5);
            animation:fadeIn 0.5s ease;
        }

        .payment-box img{
            width:180px;
            height:180px;
            object-fit:cover;
            border-radius:15px;
            margin-bottom:20px;
            box-shadow:0 5px 20px rgba(0,0,0,0.4);
        }

        .success-icon{
            font-size:55px;
            margin-bottom:15px;
        }

        h1{
            color:#1db954;
            margin-bottom:10px;
        }

        p{
            color:#cbd5e1;
            line-height:1.6;
        }

        .song-title{
            margin-top:20px;
            font-size:22px;
            font-weight:bold;
            color:white;
        }

        .artist{
            color:#94a3b8;
            margin-bottom:20px;
        }

        .reference{
            margin-top:20px;
            background:#0f172a;
            padding:12px;
            border-radius:10px;
            color:#1db954;
            font-weight:bold;
        }

        .buttons{
            margin-top:25px;
            display:flex;
            gap:15px;
            justify-content:center;
        }

        .buttons a{
            text-decoration:none;
            padding:12px 20px;
            border-radius:10px;
            color:white;
            font-weight:bold;
            transition:0.3s;
        }

        .dashboard-btn{ background:#1db954; }
        .dashboard-btn:hover{ background:#17a44a; }

        .music-btn{ background:#334155; }
        .music-btn:hover{ background:#475569; }

        @keyframes fadeIn{
            from{ opacity:0; transform:translateY(20px); }
            to{ opacity:1; transform:translateY(0); }
        }
    </style>
</head>

<body>

<div class="payment-box">

    <div class="success-icon">🎉</div>

    <img src="<?php echo $cover; ?>">

    <h1>Payment Submitted</h1>

    <p>
        Your payment request has been received successfully.
        AUOM will verify it shortly.
    </p>

    <div class="song-title">
        <?php echo $title; ?>
    </div>

    <div class="artist">
        by <?php echo $artist; ?>
    </div>

    <div class="reference">
        Ref: <?php echo $reference; ?>
    </div>

    <p style="margin-top:20px;">
        Once approved, it will appear in your My Music library.
    </p>

    <div class="buttons">

        <a href="dashboard.php" class="dashboard-btn">
            Dashboard
        </a>

        <a href="mymusic.php" class="music-btn">
            My Music
        </a>

    </div>

</div>

</body>
</html>