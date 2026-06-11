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

    <title>Upload Music - AUOM</title>
    <link rel="stylesheet" href="dashboard.css">

</head>
<body>

<div class="upload-container">

    <div class="upload-box">

        <h2>Upload Your Music</h2>

        <form action="process_upload.php" method="POST" enctype="multipart/form-data">

            <input
                type="text"
                name="title"
                placeholder="Song Title"
                required
            >

            <br><br>

            <input
                type="text"
                name="artist"
                placeholder="Artist Name"
                required
            >

            <br><br>

            <input
                type="number"
                name="price"
                placeholder="Price in USD"
                min="3"
                step="0.01"
                required
            >

            <p style="
                color:#94a3b8;
                font-size:13px;
                margin-top:8px;
                margin-bottom:15px;
            ">
                Minimum Price: $3<br>
                Suggested Pricing:<br>
                🎵 Single: $3 - $5<br>
                🔥 Hit Song: $5 - $10<br>
                💿 EP / Album: $10 - $25
            </p>

            <label>Cover Image</label><br>

            <input
                type="file"
                name="cover"
                accept="image/*"
                required
            >

            <br><br>

            <label>Audio File</label><br>

            <input
                type="file"
                name="audio"
                accept="audio/*"
                required
            >

            <br><br>

            <button type="submit">
                Upload Music
            </button>

        </form>

    </div>

</div>

</body>
</html>