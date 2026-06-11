<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","civic");

$user = $_SESSION['user'];

/*
|--------------------------------------------------------------------------
| Calculate Total Artist Earnings
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

WHERE songs.user = '$user'
";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

$total_earnings = $data['total_earnings'];

if(!$total_earnings){
    $total_earnings = 0;
}

/*
|--------------------------------------------------------------------------
| Calculate Approved Withdrawals
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Available Balance
|--------------------------------------------------------------------------
*/

$available_balance = $total_earnings - $total_withdrawn;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Withdrawals - AUOM</title>
    <link rel="stylesheet" href="dashboard.css">

    <style>

    .withdraw-box{
        max-width:700px;
        margin:50px auto;
        background:#1e293b;
        padding:30px;
        border-radius:15px;
    }

    .balance{
        font-size:32px;
        color:#1db954;
        margin-bottom:20px;
    }

    .info{
        margin-top:15px;
        color:#cbd5e1;
    }

    .warning{
        background:#7f1d1d;
        padding:15px;
        border-radius:10px;
        margin-top:20px;
    }

    .success{
        background:#064e3b;
        padding:15px;
        border-radius:10px;
        margin-top:20px;
    }

    </style>
</head>
<body>

<div class="withdraw-box">

    <h1>🏦 Withdrawals</h1>

    <div class="balance">
        $<?php echo number_format($available_balance,2); ?>
    </div>

    <div class="info">
        Available Withdrawal Balance<br></br>
        Pending Transactions are reserved and processed within 24-48hours
    </div>

    <div class="info">
        Minimum Withdrawal: $50
    </div>

    <?php if($available_balance < 50){ ?>

    <div class="warning">
        You are below the minimum withdrawal limit of $50.
    </div>

<?php } else { ?>

    <div class="success">
        You are eligible to request a withdrawal.
    </div>

    <hr style="margin:30px 0; border-color:#334155;">

    <h2>Request Withdrawal</h2>

    <form action="process_withdrawal.php" method="POST">

        <label>Withdrawal Amount ($)</label><br><br>

        <input type="number"
               name="amount"
               step="0.01"
               min="50"
               max="<?php echo $available_balance; ?>"
               required
               style="width:100%;padding:12px;border-radius:8px;border:none;">

        <br><br>

        <label>Withdrawal Method</label><br><br>

        <select name="method"
                id="method"
                onchange="showFields()"
                required
                style="width:100%;padding:12px;border-radius:8px;">

            <option value="">Select Method</option>

            <option value="Mobile Money">
                Mobile Money
            </option>

            <option value="Bank Transfer">
                Bank Transfer
            </option>

            <option value="Crypto">
                Crypto
            </option>

        </select>

        <br><br>

        <!-- MOBILE MONEY -->

        <div id="mobileFields" style="display:none;">
            <label>Enter Mobile Money Account Details to receive Funds</label>

            <input type="text"
                   name="mobile_name"
                   placeholder="Full Name"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

            <br><br>

            <input type="text"
                   name="mobile_phone"
                   placeholder="Phone Number"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

            <br><br>

            <input type="text"
                   name="mobile_network"
                   placeholder="Network (MTN, Airtel, TNM, etc)"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

        </div>

        <!-- BANK -->

        <div id="bankFields" style="display:none;">
            <label>Enter Bank Account Details to receive Funds</label>

            <input type="text"
                   name="account_name"
                   placeholder="Account Name"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

            <br><br>

            <input type="text"
                   name="account_number"
                   placeholder="Account Number"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

            <br><br>

            <input type="text"
                   name="bank_name"
                   placeholder="Bank Name"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

        </div>

        <!-- CRYPTO -->

        <div id="cryptoFields" style="display:none;">
            <label>Enter Crypto Wallet Details to receive Funds</label>

            <input type="text"
                   name="wallet_address"
                   placeholder="Wallet Address"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

            <br><br>

            <input type="text"
                   name="coin_type"
                   placeholder="USDT, Bitcoin, Ethereum"
                   style="width:100%;padding:12px;border-radius:8px;border:none;">

        </div>

        <br>

        <label>
            <input type="checkbox" required>
            I confirm that all withdrawal information provided is correct.
        </label>

        <br><br>

        <button type="submit"
                style="background:#1db954;color:white;padding:12px 20px;border:none;border-radius:8px;cursor:pointer;">

            Submit Withdrawal Request

        </button>

    </form>

<?php } ?>

<hr style="margin:40px 0; border-color:#334155;">

<h2>Withdrawal History</h2>

<?php

$history = $conn->query("
SELECT *
FROM withdrawals
WHERE user='$user'
ORDER BY id DESC
");

if($history->num_rows > 0){

?>

<table width="100%" cellpadding="12">

<tr style="background:#0f172a;">

    <th>Amount</th>
    <th>Method</th>
    <th>Status</th>
    <th>Date</th>

</tr>

<?php

while($row = $history->fetch_assoc()){

?>

<tr>

    <td>
        $<?php echo number_format($row['amount'],2); ?>
    </td>

    <td>
        <?php echo $row['method']; ?>
    </td>

    <td>

        <?php

        if($row['status'] == 'Pending'){

            echo "<span style='color:orange;font-weight:bold;'>Pending</span>";

        }elseif($row['status'] == 'Completed'){

            echo "<span style='color:#1db954;font-weight:bold;'>Completed</span>";

        }elseif($row['status'] == 'Rejected'){

            echo "<span style='color:red;font-weight:bold;'>Rejected</span>";

        }

        ?>

    </td>

    <td>
        <?php echo $row['created_at']; ?>
    </td>

</tr>

<?php
}
?>

</table>

<?php

}else{

    echo "<p>No withdrawal requests yet.</p>";

}

?>
</div>

<script>

function showFields(){

    let method =
    document.getElementById("method").value;

    document.getElementById("mobileFields").style.display = "none";
    document.getElementById("bankFields").style.display = "none";
    document.getElementById("cryptoFields").style.display = "none";

    if(method == "Mobile Money"){
        document.getElementById("mobileFields").style.display = "block";
    }

    if(method == "Bank Transfer"){
        document.getElementById("bankFields").style.display = "block";
    }

    if(method == "Crypto"){
        document.getElementById("cryptoFields").style.display = "block";
    }

}

</script>

<hr style="margin:40px 0; border-color:#334155;">

<h2 style="color:orange;">🟡 Pending Withdrawals</h2>

<?php

$pending = $conn->query("
SELECT *
FROM withdrawals
WHERE user='$user'
AND status='Pending'
ORDER BY id DESC
");

if($pending->num_rows > 0){

    while($row = $pending->fetch_assoc()){

        echo "
        <div style='background:#0f172a;padding:15px;margin-top:10px;border-radius:10px;'>

            <strong>$".$row['amount']."</strong><br>

            ".$row['method']."<br>

            ".$row['created_at']."

        </div>
        ";
    }

}else{

    echo "<p>No pending withdrawals.</p>";
}

?>


<hr style="margin:30px 0; border-color:#334155;">

<h2 style="color:#1db954;">🟢 Completed Withdrawals</h2>

<?php

$completed = $conn->query("
SELECT *
FROM withdrawals
WHERE user='$user'
AND status='Completed'
ORDER BY id DESC
");

if($completed->num_rows > 0){

    while($row = $completed->fetch_assoc()){

        echo "
        <div style='background:#0f172a;padding:15px;margin-top:10px;border-radius:10px;'>

            <strong>$".$row['amount']."</strong><br>

            ".$row['method']."<br>

            ".$row['created_at']."

        </div>
        ";
    }

}else{

    echo "<p>No completed withdrawals yet.</p>";
}

?>

<hr style="margin:30px 0; border-color:#334155;">

<h2 style="color:red;">🔴 Rejected Withdrawals</h2>

<?php

$rejected = $conn->query("
SELECT *
FROM withdrawals
WHERE user='$user'
AND status='Rejected'
ORDER BY id DESC
");

if($rejected->num_rows > 0){

    while($row = $rejected->fetch_assoc()){

        echo "
        <div style='background:#0f172a;padding:15px;margin-top:10px;border-radius:10px;'>

            <strong>$".$row['amount']."</strong><br>

            ".$row['method']."<br>

            ".$row['created_at']."

        </div>
        ";
    }

}else{

    echo "<p>No rejected withdrawals.</p>";
}

?>
</body>
</html>