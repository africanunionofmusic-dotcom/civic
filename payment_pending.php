<!DOCTYPE html>
<html>
<head>
    <title>Payment Pending - AUOM</title>

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
            background-position:center;

            color:white;
        }

        .pending-box{
            width:430px;
            background:#1e293b;
            padding:40px;
            border-radius:20px;
            text-align:center;

            box-shadow:0 10px 30px rgba(0,0,0,0.4);

            animation:fadeIn 0.5s ease;
        }

        .icon{
            font-size:60px;
            margin-bottom:20px;
        }

        h1{
            color:#1db954;
            margin-bottom:15px;
        }

        p{
            color:#cbd5e1;
            line-height:1.7;
            margin-bottom:15px;
        }

        .btn{
            display:inline-block;
            margin-top:20px;
            padding:14px 22px;

            background:#1db954;
            color:white;

            text-decoration:none;
            border-radius:10px;

            font-weight:bold;
            transition:0.3s;
        }

        .btn:hover{
            background:#17a44a;
        }

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(20px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }

    </style>

</head>

<body>

<div class="pending-box">

    <div class="icon">⏳</div>

    <h1>Payment Pending</h1>

    <p>
        Your proof of payment has been submitted successfully.
    </p>

    <p>
        AUOM will verify your payment shortly.
        Once approved, your music will automatically
        appear in <strong>My Music</strong>.
    </p>

    <a href="dashboard.php" class="btn">
        Back to Dashboard
    </a>

</div>

</body>
</html>