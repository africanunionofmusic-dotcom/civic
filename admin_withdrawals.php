<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

if($_SESSION['user'] != 'admin'){
    die("Access Denied");
}

$conn = new mysqli("localhost","root","","civic");

$pending_count = $conn->query("
SELECT COUNT(*) AS total
FROM withdrawals
WHERE status='Pending'
")->fetch_assoc()['total'];

$completed_count = $conn->query("
SELECT COUNT(*) AS total
FROM withdrawals
WHERE status='Completed'
")->fetch_assoc()['total'];

$rejected_count = $conn->query("
SELECT COUNT(*) AS total
FROM withdrawals
WHERE status='Rejected'
")->fetch_assoc()['total'];

$pending = $conn->query("
SELECT *
FROM withdrawals
WHERE status='Pending'
ORDER BY id DESC
");

$completed = $conn->query("
SELECT *
FROM withdrawals
WHERE status='Completed'
ORDER BY id DESC
");

$rejected = $conn->query("
SELECT *
FROM withdrawals
WHERE status='Rejected'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM Withdrawals</title>

    <link rel="stylesheet" href="dashboard.css">

    <style>

    .container{
        max-width:1200px;
        margin:40px auto;
        padding:20px;
    }

    .stats-box{
        display:flex;
        gap:20px;
        margin-bottom:40px;
    }

    .stat-card{
        flex:1;
        background:#1e293b;
        padding:25px;
        border-radius:15px;
        text-align:center;
        font-size:22px;
        font-weight:bold;
    }

    .withdraw-card{
        background:#1e293b;
        padding:20px;
        border-radius:15px;
        margin-bottom:20px;
    }

    .withdraw-card h3{
        color:#1db954;
        margin-bottom:10px;
    }

    .actions{
        margin-top:20px;
    }

    .approve{
        background:#1db954;
        color:white;
        padding:10px 15px;
        text-decoration:none;
        border-radius:8px;
        margin-right:10px;
    }

    .reject{
        background:red;
        color:white;
        padding:10px 15px;
        text-decoration:none;
        border-radius:8px;
    }

    h2{
        margin:40px 0 20px;
    }

    .empty{
        color:#94a3b8;
        margin-bottom:20px;
    }

    </style>

</head>
<body>

<div class="container">

<h1>🏦 AUOM Withdrawals</h1>

<br>

<div class="stats-box">

    <div class="stat-card">
        🟡 Pending
        <br><br>
        <?php echo $pending_count; ?>
    </div>

    <div class="stat-card">
        🟢 Completed
        <br><br>
        <?php echo $completed_count; ?>
    </div>

    <div class="stat-card">
        🔴 Rejected
        <br><br>
        <?php echo $rejected_count; ?>
    </div>

</div>

<!-- PENDING -->

<h2 style="color:orange;">🟡 Pending Withdrawals</h2>

<?php

if($pending->num_rows > 0){

while($row = $pending->fetch_assoc()){

?>

<div class="withdraw-card">

    <h3><?php echo $row['user']; ?></h3>

    <p>
        <strong>Amount:</strong>
        $<?php echo number_format($row['amount'],2); ?>
    </p>

    <p>
        <strong>Method:</strong>
        <?php echo $row['method']; ?>
    </p>

    <p>
        <strong>Details:</strong>
        <?php echo $row['details']; ?>
    </p>

    <p>
        <strong>Date:</strong>
        <?php echo $row['created_at']; ?>
    </p>

    <div class="actions">

        <a
        class="approve"
        onclick="return confirm('Approve this withdrawal?')"
        href="approve_withdrawal.php?id=<?php echo $row['id']; ?>">
        Approve
        </a>

        <a
        class="reject"
        onclick="return confirm('Reject this withdrawal?')"
        href="reject_withdrawal.php?id=<?php echo $row['id']; ?>">
        Reject
        </a>

    </div>

</div>

<?php

}

}else{

echo "<p class='empty'>No pending withdrawals.</p>";

}

?>

<!-- COMPLETED -->

<h2 style="color:#1db954;">🟢 Completed Withdrawals</h2>

<?php

if($completed->num_rows > 0){

while($row = $completed->fetch_assoc()){

?>

<div class="withdraw-card">

    <h3><?php echo $row['user']; ?></h3>

    <p>
        <strong>Amount:</strong>
        $<?php echo number_format($row['amount'],2); ?>
    </p>

    <p>
        <strong>Method:</strong>
        <?php echo $row['method']; ?>
    </p>

    <p>
        <strong>Details:</strong>
        <?php echo $row['details']; ?>
    </p>

    <p>
        <strong>Date:</strong>
        <?php echo $row['created_at']; ?>
    </p>

</div>

<?php

}

}else{

echo "<p class='empty'>No completed withdrawals.</p>";

}

?>

<!-- REJECTED -->

<h2 style="color:red;">🔴 Rejected Withdrawals</h2>

<?php

if($rejected->num_rows > 0){

while($row = $rejected->fetch_assoc()){

?>

<div class="withdraw-card">

    <h3><?php echo $row['user']; ?></h3>

    <p>
        <strong>Amount:</strong>
        $<?php echo number_format($row['amount'],2); ?>
    </p>

    <p>
        <strong>Method:</strong>
        <?php echo $row['method']; ?>
    </p>

    <p>
        <strong>Details:</strong>
        <?php echo $row['details']; ?>
    </p>

    <p>
        <strong>Date:</strong>
        <?php echo $row['created_at']; ?>
    </p>

</div>

<?php

}

}else{

echo "<p class='empty'>No rejected withdrawals.</p>";

}

?>

</div>

</body>
</html>