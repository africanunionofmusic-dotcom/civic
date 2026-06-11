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

// TOTAL USERS
$users = $conn->query("SELECT COUNT(*) as total FROM users");
$total_users = $users->fetch_assoc()['total'];

// TOTAL SONGS
$songs = $conn->query("SELECT COUNT(*) as total FROM songs");
$total_songs = $songs->fetch_assoc()['total'];

// TOTAL PURCHASES
$purchases = $conn->query("SELECT COUNT(*) as total FROM purchases");
$total_purchases = $purchases->fetch_assoc()['total'];

// PENDING PAYMENTS
$pending = $conn->query("SELECT COUNT(*) as total FROM payments WHERE status='pending'");
$total_pending = $pending->fetch_assoc()['total'];

// TOTAL REVENUE
$revenue = $conn->query("
SELECT SUM(songs.price) as total
FROM purchases
JOIN songs ON purchases.song_id = songs.id
");

$total_revenue = $revenue->fetch_assoc()['total'];

if(!$total_revenue){
    $total_revenue = 0;
}

// FETCH PENDING PAYMENTS WITH SONG DETAILS
$payments = $conn->query("
SELECT payments.*, songs.title, songs.artist, songs.cover
FROM payments
JOIN songs ON payments.song_id = songs.id
WHERE payments.status='pending'
ORDER BY payments.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>AUOM Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>

<body>

<div class="admin-container">

    <!-- SIDEBAR -->
    <div class="admin-sidebar">

        <h2>AUOM ADMIN</h2>

        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>

            <li><a href="admin_users.php">Users</a></li>

            <li><a href="admin_songs.php">Songs</a></li>

            <li><a href="admin_purchases.php">Purchases</a></li>

            <li><a href="dashboard.php">Music</a></li>

            <li><a href="earnings.php">Earnings</a></li>

            <li><a href="admin_withdrawals.php">Withdrawals</a></li>

            <li><a href="logout.php">Logout</a></li>
        </ul>

    </div>

    <!-- MAIN -->
    <div class="admin-main">

        <h1>🎧 AUOM Control Center</h1>

        <!-- STATS -->
        <div class="stats-grid">

            <a href="admin_users.php" class="stat-card">
                <h3>Total Users</h3>
                <p><?php echo $total_users; ?></p>
            </a>

            <a href="admin_songs.php" class="stat-card">
                <h3>Total Songs</h3>
                <p><?php echo $total_songs; ?></p>
            </a>

            <a href="admin_purchases.php" class="stat-card">
                <h3>Total Purchases</h3>
                <p><?php echo $total_purchases; ?></p>
            </a>

            <div class="stat-card">
                <h3>Pending Payments</h3>
                <p><?php echo $total_pending; ?></p>
            </div>

            <div class="stat-card revenue-card">
                <h3>Total Revenue</h3>
                <p>$<?php echo $total_revenue; ?></p>
            </div>

        </div>

        <!-- PAYMENTS -->
        <div class="payments-section">

            <h2>Pending Payments</h2>

            <?php
            if($payments->num_rows > 0){

                while($row = $payments->fetch_assoc()){
            ?>

            <div class="payment-card">

                <!-- LEFT -->
                <div class="payment-info">

                    <img src="<?php echo $row['proof']; ?>" class="proof-image">

                    <div class="payment-details">

                        <h3><?php echo $row['title']; ?></h3>

                        <p>
                            Artist: <?php echo $row['artist']; ?>
                        </p>

                        <p>
                            Buyer: <?php echo $row['user']; ?>
                        </p>

                        <p>
                            Method: <?php echo strtoupper($row['method']); ?>
                        </p>

                    </div>

                </div>

                <!-- RIGHT -->
                <div class="payment-actions">

                    <a href="approve.php?id=<?php echo $row['id']; ?>" class="approve-btn">
                        Approve
                    </a>

                    <a href="reject.php?id=<?php echo $row['id']; ?>" class="reject-btn">
                        Reject
                    </a>

                </div>

            </div>

            <?php
                }

            } else {

                echo "<p>No pending payments.</p>";

            }
            ?>

        </div>

    </div>

</div>

</body>
</html>