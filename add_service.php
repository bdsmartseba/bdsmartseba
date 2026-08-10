<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = $_POST['price'];
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);

    $sql = "INSERT INTO services (service_name, price, instructions) VALUES ('$service_name', '$price', '$instructions')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('নতুন সেবা সফলভাবে যোগ করা হয়েছে এবং সাইডবারে যুক্ত হয়ে গেছে!'); window.location='add_service.php';</script>";
    } else {
        echo "<script>alert('সমস্যা হয়েছে!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>নতুন সেবা যোগ করুন - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #e8f5e9; display: flex; }
        .sidebar { width: 250px; background-color: #2e7d32; color: white; height: 100vh; padding: 20px; position: fixed; box-sizing: border-box; overflow-y: auto; }
        .sidebar h2 { color: white; font-size: 20px; text-align: center; margin-bottom: 20px; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-bottom: 1px solid #1b5e20; border-radius: 5px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover { background-color: #1b5e20; }
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); box-sizing: border-box; }
        .form-card { background: white; padding: 30px; border-radius: 10px; width: 500px; box-shadow: 0 4px 10px rgba(0,128,0,0.1); border-top: 5px solid #2e7d32; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #c8e6c9; border-radius: 5px; box-sizing: border-box; font-family: sans-serif; }
        button { background-color: #2e7d32; color: white; padding: 12px; width: 100%; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #1b5e20; }
        .menu-heading { font-size: 12px; color: #c8e6c9; margin: 15px 0 5px 0; text-transform: uppercase; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>স্মার্ট সেবা</h2>
        <a href="dashboard.php">ড্যাশবোর্ড</a>
        <a href="deposit.php">বিকাশ রিচার্জ</a>
        <a href="add_service.php">নতুন সেবা যোগ করুন</a>
        
        <div class="menu-heading">সেবাসমূহ</div>
        <?php
        $s_query = mysqli_query($conn, "SELECT * FROM services");
        while($s_row = mysqli_fetch_assoc($s_query)){
            echo '<a href="service_view.php?id='.$s_row['id'].'">'.$s_row['service_name'].'</a>';
        }
        ?>
        <a href="logout.php" style="margin-top: 20px; background: #c62828; text-align: center;">লগআউট</a>
    </div>

    <div class="main-content">
        <h2>নতুন সেবা বা প্রোডাক্ট তৈরি করুন</h2>
        <div class="form-card">
            <form action="add_service.php" method="POST">
                <label>সেবার নাম (যেমন: স্মার্ট এন আইডি কার্ড):</label>
                <input type="text" name="service_name" placeholder="সেবার নাম লিখুন" required>
                
                <label>মূল্য কত টাকা কাটা হবে?:</label>
                <input type="number" name="price" placeholder="যেমন: ১০০" required>
                
                <label>কাস্টমারের জন্য নির্দেশনা বা কি কি তথ্য দিতে হবে লিখুন:</label>
                <textarea name="instructions" rows="4" placeholder="যেমন: ফরম নম্বর, স্লিপ নম্বর অথবা বোর্ডের নাম দিন..." required></textarea>
                
                <button type="submit">সেবা ও মেনু তৈরি করুন</button>
            </form>
        </div>
    </div>

</body>
</html>