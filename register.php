<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "connect.php";

// Check if form was submitted
if(isset($_POST['fullname'], $_POST['email'], $_POST['password'])) {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check_result = $check->get_result();

    if($check_result->num_rows > 0){
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Email Exists</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>

        <div class='card'>
            <h1>⚠ Email Already Registered</h1>

            <p class='switch'>
                This email already exists in AUOM.
            </p>

            <a href='signup.php' class='login-btn'>
                Try Again
            </a>
        </div>

        </body>
        </html>
        ";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if($stmt->execute()){
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">

    <h1>✅ Signup Successful!</h1>

    <p class="switch">
        Your AUOM account has been created successfully.
    </p>

    <a href="index.php" class="login-btn">
        Login Now
    </a>

</div>

</body>
</html>

<?php
    } else {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Error</title>
            <link rel='stylesheet' href='style.css'>
        </head>
        <body>

        <div class='card'>
            <h1>❌ Signup Failed</h1>

            <p class='switch'>
                " . $stmt->error . "
            </p>

            <a href='signup.php' class='login-btn'>
                Go Back
            </a>
        </div>

        </body>
        </html>
        ";
    }

    $stmt->close();
    $check->close();

} else {

    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <title>Missing Fields</title>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>

    <div class='card'>
        <h1>⚠ Missing Fields</h1>

        <p class='switch'>
            Please fill in all required fields.
        </p>

        <a href='signup.php' class='login-btn'>
            Go Back
        </a>
    </div>

    </body>
    </html>
    ";
}

$conn->close();
?>