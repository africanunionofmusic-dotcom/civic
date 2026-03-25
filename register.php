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
        echo "This email is already registered! <a href='signup.php'>Try again</a>";
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if($stmt->execute()){
        echo "Signup successful! <a href='login.php'>Login now</a>";
    } else {
        echo "Error inserting user: " . $stmt->error;
    }

    $stmt->close();
    $check->close();
} else {
    echo "Please fill in all fields!";
}

$conn->close();
?>