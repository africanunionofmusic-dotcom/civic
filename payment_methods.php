<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

$song_id = $_GET['song_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Choose Payment Method - AUOM</title>

    <style>
        body{
            margin:0;
            font-family:Arial;
            background:linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)),
            url("images/music.jpg");
            background-size:cover;
            color:white;
        }

        .container{
            padding:50px;
            text-align:center;
        }

        h1{
            margin-bottom:30px;
        }

        .cards{
            display:flex;
            justify-content:center;
            gap:25px;
            flex-wrap:wrap;
        }

        .card{
            background:#1e293b;
            width:250px;
            padding:25px;
            border-radius:15px;
            box-shadow:0 10px 30px rgba(0,0,0,0.4);
            transition:0.3s;
        }

        .card:hover{
            transform:translateY(-5px);
        }

        .card h2{
            color:#1db954;
        }

        .btn{
            display:inline-block;
            margin-top:15px;
            padding:10px 15px;
            background:#1db954;
            color:white;
            text-decoration:none;
            border-radius:8px;
        }

        .bank{
            color:#60a5fa;
        }

        .crypto{
            color:#f59e0b;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>💳 Choose Payment Method</h1>

    <div class="cards">

        <!-- MOBILE MONEY -->
        <div class="card">
            <h2>📱 Mobile Money</h2>
            <p>Send payment to:</p>
            <p><b>+256 770 500 725</b></p>
            <p><b>+265 983 025 862</b></p>
            <p>Account: AUOM</p>

            <a class="btn" href="upload_proof.php?song_id=<?php echo $song_id; ?>&method=mobile">
                I Have Paid
            </a>
        </div>

        <!-- BANK -->
        <div class="card">
            <h2 class="bank">🏦 Bank Transfer</h2>
            <p>Stanbic Bank</p>
            <p>USD: 9030025275100</p>
            <p>Local Currency: 9030024829218</p>
            <p>Account: AUOM</p>

            <a class="btn" href="upload_proof.php?song_id=<?php echo $song_id; ?>&method=bank">
                I Have Paid
            </a>
        </div>

        <!-- CRYPTO -->
        <div class="card">
            <h2 class="crypto">₿ Crypto</h2>
            <p>USDT / BTC Wallet</p>
            <p>Send to AUOM Wallet</p>

            <a class="btn" href="upload_proof.php?song_id=<?php echo $song_id; ?>&method=crypto">
                I Have Paid
            </a>
        </div>

    </div>

</div>

</body>
</html>