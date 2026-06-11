<?php
session_start();

$conn = new mysqli("localhost", "root", "", "civic");

$user = $_SESSION['user'];

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data

$artist = $_POST['artist'];
$title = $_POST['title'];
$price = $_POST['price'];

if($price < 3){
    die("Minimum song price on AUOM is $3");
}
// Files
$cover = $_FILES['cover'];
$audio = $_FILES['audio'];

// Rename files to avoid duplicates
$coverName = time() . "_" . $cover['name'];
$audioName = time() . "_" . $audio['name'];

// Paths
$coverPath = "uploads/covers/" . $coverName;
$audioPath = "uploads/audio/" . $audioName;
$previewPath = "uploads/previews/preview_" . $audioName;

// Move uploaded files
move_uploaded_file($cover['tmp_name'], $coverPath);
move_uploaded_file($audio['tmp_name'], $audioPath);

// 🎧 Create 15-sec preview using FFmpeg
$command = "ffmpeg -i \"$audioPath\" -t 15 -c copy \"$previewPath\"";
exec($command);

// Save to database
$sql = "INSERT INTO songs (artist, title, price, cover, audio, preview, user)
VALUES ('$artist', '$title', '$price', '$coverPath', '$audioPath', '$previewPath', '$user')";

if ($conn->query($sql) === TRUE) {

    $conn->query("
    UPDATE users
    SET role='artist'
    WHERE fullname='$user'
    ");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Successful - AUOM</title>

    <link rel="stylesheet" href="dashboard.css">

    <style>
        body{
            margin:0;
            padding:0;
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;

            background-image:
            linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
            url("images/music.jpg");

            background-size:cover;
            background-position:center;

            font-family:Arial;
            color:white;
        }

        .success-box{
            width:420px;
            background:#1e293b;
            padding:35px;
            border-radius:20px;
            text-align:center;
            box-shadow:0 10px 40px rgba(0,0,0,0.5);

            animation:fadeIn 0.5s ease;
        }

        .success-box img{
            width:180px;
            height:180px;
            object-fit:cover;
            border-radius:15px;
            margin-bottom:20px;
            box-shadow:0 5px 20px rgba(0,0,0,0.4);
        }

        .success-box h1{
            color:#1db954;
            margin-bottom:10px;
        }

        .success-box p{
            color:#cbd5e1;
            margin-bottom:10px;
        }

        .song-title{
            font-size:22px;
            font-weight:bold;
            margin-top:15px;
            color:white;
        }

        .artist-name{
            color:#94a3b8;
            margin-bottom:20px;
        }

        .buttons{
            display:flex;
            gap:15px;
            justify-content:center;
            margin-top:25px;
        }

        .buttons a{
            padding:12px 20px;
            border-radius:10px;
            text-decoration:none;
            color:white;
            transition:0.3s;
            font-weight:bold;
        }

        .dashboard-btn{
            background:#1db954;
        }

        .dashboard-btn:hover{
            background:#17a44a;
        }

        .upload-btn{
            background:#334155;
        }

        .upload-btn:hover{
            background:#475569;
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

<div class="success-box">

    <img src="<?php echo $coverPath; ?>">

    <h1>🎉 Upload Successful</h1>

    <p>Your music is now live on AUOM.</p>

    <div class="song-title">
        <?php echo $title; ?>
    </div>

    <div class="artist-name">
        by <?php echo $artist; ?>
    </div>

    <p>Fans can now discover and purchase your music.</p>

    <div class="buttons">

        <a href="dashboard.php" class="dashboard-btn">
            Go To Dashboard
        </a>

        <a href="upload.php" class="upload-btn">
            Upload Another
        </a>

    </div>

</div>

</body>
</html>

<?php

} else {
    echo "Error: " . $conn->error;
}