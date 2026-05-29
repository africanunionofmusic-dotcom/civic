<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: index.php");
    exit();
}

if($_SESSION['user'] != 'admin'){
    echo "Access denied";
    exit();
}

$conn = new mysqli("localhost", "root", "", "civic");

$id = $_GET['id'];

// GET PAYMENT
$payment = $conn->query("
SELECT * FROM payments
WHERE id='$id'
");

$row = $payment->fetch_assoc();

$user = $row['user'];
$song_id = $row['song_id'];

// INSERT INTO PURCHASES
$conn->query("
INSERT INTO purchases (user, song_id)
VALUES ('$user', '$song_id')
");

// UPDATE PAYMENT STATUS
$conn->query("
UPDATE payments
SET status='approved'
WHERE id='$id'
");

// REDIRECT
header("Location: admin_dashboard.php");
exit();
?>