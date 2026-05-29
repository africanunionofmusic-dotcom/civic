<?php
session_start();
$conn = new mysqli("localhost","root","","civic");

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<h2>All Users</h2>

<?php while($row = $result->fetch_assoc()){ ?>

<div class="payment-card">
    <div>
        <strong>Name:</strong> <?php echo $row['fullname']; ?><br>
        <strong>Email:</strong> <?php echo $row['email']; ?>
    </div>
</div>

<?php } ?>