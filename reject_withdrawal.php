<?php
session_start();

if($_SESSION['user'] != 'admin'){
    die("Access denied");
}

$conn = new mysqli("localhost","root","","civic");

$id = $_GET['id'];

$conn->query("
UPDATE withdrawals
SET status='Rejected'
WHERE id='$id'
");

header("Location: admin_withdrawals.php");
exit();
?>