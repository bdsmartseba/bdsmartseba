<?php
session_start();
include 'db.php';

// ইউজার লগইন করা আছে কিনা নিশ্চিত করা
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $sender_number = $_POST['sender_number'];
    $txid = $_POST['txid'];
    $status = 'Pending'; // অ্যাডমিন কনফার্ম না করা পর্যন্ত স্ট্যাটাস পেন্ডিং থাকবে

    // ডাটাবেজে রিচার্জের রিকোয়েস্ট জমা দেওয়া
    $sql = "INSERT INTO deposits (user_id, amount, sender_number, txid, status) VALUES ('$user_id', '$amount', '$sender_number', '$txid', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('আপনার রিচার্জ রিকোয়েস্টটি সফলভাবে পাঠানো হয়েছে! অ্যাডমিন চেক করে ব্যালেন্স আপডেট করে দিবে।'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>