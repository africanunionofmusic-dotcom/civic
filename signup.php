<?php include "connect.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>AUOM Signup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <h1>Create Account</h1>

    <form action="register.php" method="POST">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Sign Up</button>
    </form>

    <p class="switch">
        Already have an account? <a href="index.php">Login</a>
    </p>
</div>

</body>
</html>