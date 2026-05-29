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

// UPDATE STATUS
$conn->query("
UPDATE payments
SET status='rejected'
WHERE id='$id'
");

// REDIRECT
header("Location: admin_dashboard.php");
exit();
?>