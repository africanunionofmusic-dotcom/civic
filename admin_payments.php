<?php
session_start();

$conn = new mysqli("localhost", "root", "", "civic");

// OPTIONAL: simple admin lock
if(!isset($_SESSION['user']) || $_SESSION['user'] != 'admin'){
    die("Access denied");
}

$result = $conn->query("SELECT * FROM payments WHERE status='pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Payments</title>
    <style>
        body {
            background: #0f172a;
            color: white;
            font-family: Arial;
            padding: 20px;
        }

        .box {
            background: #1e293b;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        a {
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 6px;
            margin-right: 10px;
            color: white;
        }

        .approve { background: green; }
        .reject { background: red; }
    </style>
</head>
<body>

<h2>Pending Payments</h2>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
?>

<div class="box">
    <p><strong>User:</strong> <?php echo $row['user']; ?></p>
    <p><strong>Song ID:</strong> <?php echo $row['song_id']; ?></p>
    <p><strong>Reference:</strong> <?php echo $row['reference']; ?></p>

    <a class="approve" href="approve.php?id=<?php echo $row['id']; ?>">Approve</a>
    <a class="reject" href="reject.php?id=<?php echo $row['id']; ?>">Reject</a>
</div>

<?php
    }
} else {
    echo "No pending payments";
}
?>

</body>
</html>