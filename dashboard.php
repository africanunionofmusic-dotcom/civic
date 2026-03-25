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
    <title>AUOM Dashboard</title>
</head>
<body>
<h1>Welcome, <?php echo $_SESSION['user']; ?>!</h1>
<p>Welcome to the Auomusic dashboard.</p>
</body>
</html>