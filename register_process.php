<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password !== $cpassword) {
        echo "<script>alert('পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড এক হয়নি!'); window.location.href='register.php';</script>";
        exit();
    }

    // পাসওয়ার্ড হ্যাশ করা সুরক্ষার জন্য
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, mobile, email, password, balance) VALUES ('$name', '$mobile', '$email', '$hashed_password', 0)";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('রেজিস্ট্রেশন সফল হয়েছে! এখন লগইন করুন।'); window.location.href='login.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>