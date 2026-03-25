<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include "connect.php";

// Check if form is submitted
if(isset($_POST['email'], $_POST['password'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Find user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();

        // Verify password
        if(password_verify($password, $user['password'])){
            $_SESSION['user'] = $user['fullname'];
            header("Location: dashboard.php"); // redirect to dashboard
            exit();
        } else {
            echo "Wrong password! <a href='login.php'>Try again</a>";
        }
    } else {
        echo "User not found! <a href='signup.php'>Sign up</a>";
    }

    $stmt->close();
} else {
    echo "Please fill in all fields! <a href='login.php'>Go back</a>";
}

$conn->close();
?>