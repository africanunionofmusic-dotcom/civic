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
    
    <title>Upload Music</title>
    <link rel="stylesheet" href="dashboard.css">
    
</head>
<body>
<div class="upload-container">
 <div class="upload-box">

       <h2>Upload Your Music</h2>

      <form action="process_upload.php" method="POST" enctype="multipart/form-data">

        <input type="text" name="title" placeholder="Song Title" required><br><br>

        <input type="text" name="artist" placeholder="Artist Name" required><br><br>

        <input type="number" name="price" placeholder="Price (Usd/Ugx)" required><br><br>

         <label>Cover Image</label><br>
             <input type="file" name="cover" accept="image/*" required><br><br>

           <label>Audio File</label><br>
        <input type="file" name="audio" accept="audio/*" required><br><br>

        <button type="submit">Upload</button>

          </form>
    </div>
</div>
</body>
</html>
