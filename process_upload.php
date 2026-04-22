<?php
session_start();

$conn = new mysqli("localhost", "root", "", "civic");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data

$artist = $_POST['artist'];
$title = $_POST['title'];
$price = $_POST['price'];

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
$sql = "INSERT INTO songs (artist, title, price, cover, audio, preview)
        VALUES ('$artist', '$title', '$price', '$coverPath', '$audioPath', '$previewPath')";

if ($conn->query($sql) === TRUE) {
    echo "Upload successful!";
} else {
    echo "Error: " . $conn->error;
}
?>