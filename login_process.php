<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // পাসওয়ার্ড যাচাই করা
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            
            echo "<script>alert('লগইন সফল হয়েছে!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('ভুল পাসওয়ার্ড! আবার চেষ্টা করুন।'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('এই জিমেইল দিয়ে কোনো অ্যাকাউন্ট পাওয়া যায়নি!'); window.location.href='login.php';</script>";
    }
}
?>