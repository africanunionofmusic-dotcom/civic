<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

$amount = $_POST['amount'];
$method = $_POST['method'];

/*
|--------------------------------------------------------------------------
| Recalculate Available Balance
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
SUM(COUNT_PURCHASES.total_sales * songs.price * 0.80) AS total_earnings
FROM songs

LEFT JOIN
(
    SELECT song_id, COUNT(*) as total_sales
    FROM purchases
    GROUP BY song_id
) COUNT_PURCHASES

ON songs.id = COUNT_PURCHASES.song_id

WHERE songs.user='$user'
";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

$total_earnings = $data['total_earnings'];

if(!$total_earnings){
    $total_earnings = 0;
}

$withdraw_sql = "
SELECT SUM(amount) AS total_withdrawn
FROM withdrawals
WHERE user='$user'
AND status IN ('Pending','Completed')
";

$withdraw_result = $conn->query($withdraw_sql);
$withdraw_data = $withdraw_result->fetch_assoc();

$total_withdrawn = $withdraw_data['total_withdrawn'];

if(!$total_withdrawn){
    $total_withdrawn = 0;
}

$available_balance = $total_earnings - $total_withdrawn;

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if($amount < 50){
    die("Minimum withdrawal amount is $50");
}

if($amount > $available_balance){
    die("Insufficient withdrawal balance.");
}

/*
|--------------------------------------------------------------------------
| Build Details
|--------------------------------------------------------------------------
*/

$details = "";

if($method == "Mobile Money"){

    $name = $_POST['mobile_name'];
    $phone = $_POST['mobile_phone'];
    $network = $_POST['mobile_network'];

    $details =
    "Name: $name | ".
    "Phone: $phone | ".
    "Network: $network";
}

elseif($method == "Bank Transfer"){

    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $bank_name = $_POST['bank_name'];

    $details =
    "Account Name: $account_name | ".
    "Account Number: $account_number | ".
    "Bank: $bank_name";
}

elseif($method == "Crypto"){

    $wallet = $_POST['wallet_address'];
    $coin = $_POST['coin_type'];

    $details =
    "Coin: $coin | ".
    "Wallet: $wallet";
}

/*
|--------------------------------------------------------------------------
| Save Withdrawal
|--------------------------------------------------------------------------
*/

$sql = "
INSERT INTO withdrawals
(user, amount, method, details, status)

VALUES

('$user','$amount','$method','$details','Pending')
";

if($conn->query($sql)){

    // WhatsApp Notification To Admin

    $message = urlencode(
        "💸 NEW AUOM WITHDRAWAL REQUEST\n\n".
        "Artist: $user\n".
        "Amount: $$amount\n".
        "Method: $method\n\n".
        "Details:\n$details\n\n".
        "Status: Pending\n\n".
        "Login to AUOM Admin to approve or reject."
    );

    file_get_contents(
        "https://api.callmebot.com/whatsapp.php?phone=447878688244&text=$message&apikey=2171303"
    );

?>
<!DOCTYPE html>
<html>
<head>

<title>Withdrawal Submitted</title>

<link rel="stylesheet" href="dashboard.css">

<style>

body{
    margin:0;
    padding:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;

    background-image:
    linear-gradient(rgba(0,0,0,0.75),
    rgba(0,0,0,0.75)),
    url("images/music.jpg");

    background-size:cover;
    background-position:center;

    color:white;
    font-family:Arial;
}

.success-box{
    width:500px;
    background:#1e293b;
    padding:35px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 10px 40px rgba(0,0,0,0.5);
}

.success-box h1{
    color:#1db954;
    margin-bottom:15px;
}

.amount{
    font-size:30px;
    color:#1db954;
    margin:20px 0;
}

.method{
    color:#cbd5e1;
    margin-bottom:10px;
}

.status{
    background:#f59e0b;
    color:black;
    padding:10px;
    border-radius:10px;
    font-weight:bold;
    margin-top:15px;
}

.buttons{
    margin-top:25px;
    display:flex;
    gap:15px;
    justify-content:center;
}

.buttons a{
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    font-weight:bold;
}

.withdraw-btn{
    background:#1db954;
}

.dashboard-btn{
    background:#334155;
}

</style>

</head>
<body>

<div class="success-box">

    <h1>🎉 Withdrawal Request Submitted</h1>

    <div class="amount">
        $<?php echo number_format($amount,2); ?>
    </div>

    <div class="method">
        Method: <?php echo $method; ?>
    </div>

    <div class="status">
        Pending Approval
    </div>

    <p style="margin-top:20px;">
        Your request has been received successfully.
        AUOM will review and process your payout shortly.
    </p>

    <div class="buttons">

        <a href="withdrawals.php"
           class="withdraw-btn">

            View Withdrawals

        </a>

        <a href="dashboard.php"
           class="dashboard-btn">

            Dashboard

        </a>

    </div>

</div>

</body>
</html>

<?php
}