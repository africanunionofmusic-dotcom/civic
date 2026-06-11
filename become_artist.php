<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Become an Artist - AUOM</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background-image:
            linear-gradient(rgba(0,0,0,0.82), rgba(0,0,0,0.82)),
            url("images/music.jpg");

            background-size:cover;
            background-position:center;
            background-attachment:fixed;

            color:white;
        }

        .container{
            max-width:1100px;
            margin:auto;
            padding:40px 20px;
        }

        .hero{
            text-align:center;
            margin-bottom:60px;
        }

        .hero img{
            width:180px;
            margin-bottom:25px;
        }

        .hero h1{
            font-size:48px;
            margin-bottom:15px;
        }

        .hero p{
            font-size:20px;
            color:#cbd5e1;
            max-width:700px;
            margin:auto;
            line-height:1.8;
        }

        .section{
            background:rgba(30,41,59,0.95);
            padding:30px;
            border-radius:18px;
            margin-bottom:30px;
            box-shadow:0 8px 25px rgba(0,0,0,0.4);
        }

        .section h2{
            color:#1db954;
            margin-bottom:20px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .card{
            background:#0f172a;
            padding:20px;
            border-radius:15px;
            text-align:center;
        }

        .card h3{
            margin-bottom:10px;
            color:#1db954;
        }

        .split{
            display:flex;
            justify-content:space-around;
            text-align:center;
            flex-wrap:wrap;
            gap:20px;
        }

        .split-box{
            background:#0f172a;
            padding:25px;
            border-radius:15px;
            width:250px;
        }

        .split-box h3{
            color:#1db954;
            font-size:35px;
        }

        .benefits li,
        .rules li{
            margin-bottom:12px;
            list-style:none;
        }

        .benefits li::before{
            content:"✓ ";
            color:#1db954;
            font-weight:bold;
        }

        .rules li::before{
            content:"• ";
            color:#ef4444;
        }

        .verification{
            line-height:2;
        }

        .declaration{
            line-height:2;
        }

        .declaration label{
            display:block;
            margin-bottom:12px;
        }

        input[type="checkbox"]{
            margin-right:10px;
            transform:scale(1.2);
        }

        .buttons{
            margin-top:30px;
            text-align:center;
        }

        .btn{
            display:inline-block;
            padding:15px 25px;
            text-decoration:none;
            border-radius:10px;
            color:white;
            font-weight:bold;
            margin:10px;
            transition:0.3s;
        }

        .upload-btn{
            background:#1db954;
            pointer-events:none;
            opacity:0.5;
        }

        .dashboard-btn{
            background:#334155;
        }

        .upload-btn.active{
            opacity:1;
            pointer-events:auto;
        }

        .upload-btn:hover{
            background:#17a44a;
        }

        .dashboard-btn:hover{
            background:#475569;
        }

    </style>
</head>
<body>

<div class="container">

    <div class="hero">

        <img src="images/auomlogo.jpg">

        <h1>Become an AUOM Artist</h1>

        <p>
            Turn your music into income.
            Upload your songs, reach fans across Africa and the world,
            Track your sales and earn from every purchase.
        </p>

    </div>

    <div class="section">

        <h2>🎵 How AUOM Works</h2>

        <div class="cards">

            <div class="card">
                <h3>1. Upload</h3>
                <p>
                    Upload your music, artwork and pricing.
                </p>
            </div>

            <div class="card">
                <h3>2. Sell</h3>
                <p>
                    Fans discover and purchase your music.
                </p>
            </div>

            <div class="card">
                <h3>3. Earn</h3>
                <p>
                    Receive 80% of every approved sale.
                </p>
            </div>

        </div>

    </div>

    <div class="section">

        <h2>💰 Revenue Share</h2>

        <div class="split">

            <div class="split-box">
                <h3>80%</h3>
                <p>Artist Share</p>
            </div>

            <div class="split-box">
                <h3>20%</h3>
                <p>AUOM Retains</p>
            </div>

        </div>

        <br>

        <p>
            AUOM uses its share to maintain the platform,
            improve services, promotion and marketting,
            support payments and continue creating opportunities 
            for African artists.
        </p>

    </div>

    <div class="section">

        <h2>🚀 Artist Benefits</h2>

        <ul class="benefits">

            <li>Unlimited song uploads</li>
            <li>Set your own song prices</li>
            <li>Real-time sales tracking</li>
            <li>Artist Dashboard</li>
            <li>Earnings tracking</li>
            <li>Build your music catalog</li>
            <li>Reach fans across Africa and the world</li>

        </ul>

    </div>

    <div class="section">

        <h2>📜 Platform Rules</h2>

        <ul class="rules">

            
            <li>You must own the rights to uploaded music</li>
            <li>Copyright infringement is prohibited</li>
            <li>Minimum song price is $3</li>
            <li>AUOM may remove unauthorized content</li>
            <li>Accounts violating copyright policies may be suspended</li>

        </ul>

    </div>

    <div class="section">

        <h2>🔒 Verification & Withdrawals</h2>

        <div class="verification">

            <p>You can upload and sell music immediately.</p>

            <p>
                Before withdrawals are approved,
                AUOM may request:
            </p>

            <p>✓ Government-issued ID</p>
            <p>✓ Phone verification</p>
            <p>✓ Artist profile verification</p>

        </div>

    </div>

    <div class="section">

        <h2>✅ Artist Declaration</h2>

        <div class="declaration">

            <label>
                <input type="checkbox" class="agree">
                I confirm that I own or have permission to distribute all uploaded music.
            </label>

            <label>
                <input type="checkbox" class="agree">
                I understand that copyright violations may result in account suspension or permanent removal.
            </label>

            <label>
                <input type="checkbox" class="agree">
                I understand that AUOM may require identity verification before withdrawals are processed.
            </label>

        </div>

        <div class="buttons">

            <a href="accept_artist_terms.php"
               id="uploadBtn"
               class="btn upload-btn">

               🚀 Upload My First Song

            </a>

            <a href="dashboard.php"
               class="btn dashboard-btn">

               🏠 Back To Dashboard

            </a>

        </div>

    </div>

</div>

<script>

const checks = document.querySelectorAll(".agree");
const button = document.getElementById("uploadBtn");

checks.forEach(check => {

    check.addEventListener("change", () => {

        let allChecked = true;

        checks.forEach(c => {

            if(!c.checked){
                allChecked = false;
            }

        });

        if(allChecked){
            button.classList.add("active");
        }else{
            button.classList.remove("active");
        }

    });

});

</script>

</body>
</html>