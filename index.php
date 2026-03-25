<?php include "connect.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>AUOM Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <h1>Login</h1>

    <form action="login_process.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

    <p class="switch">
        Don’t have an account? <a href="signup.php">Sign Up</a>
    </p>
</div>

</body>
</html>