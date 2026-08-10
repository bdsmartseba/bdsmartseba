<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit_recharge'])) {
    $amount = floatval($_POST['amount']);
    $trxid = mysqli_real_escape_string($conn, $_POST['trxid']);

    if ($amount < 100) {
        echo "<script>alert('সতর্কবার্তা: কমপক্ষে ১০০ টাকা বা তার বেশি রিচার্জ করতে হবে!'); window.location='recharge.php';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO deposits (user_id, amount, trxid, status) VALUES ('$user_id', '$amount', '$trxid', 'Pending')");
        echo "<script>alert('রিচার্জ রিকোয়েস্ট সফলভাবে জমা হয়েছে!'); window.location='recharge.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>রিচার্জ - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; background: #e8f5e9; display: flex; margin: 0; }
        .sidebar { width: 230px; background: #1b5e20; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h3 { text-align: center; color: #fff; }
        .sidebar a { display: block; color: #fff; padding: 12px; text-decoration: none; border-bottom: 1px solid #2e7d32; }
        .sidebar a:hover { background: #2e7d32; }
        .main { margin-left: 250px; padding: 20px; width: calc(100% - 250px); box-sizing: border-box; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .pay-box { background: #fdfdfd; padding: 15px; border: 2px solid #2e7d32; border-radius: 8px; margin-bottom: 20px; }
        .pay-box p { margin: 12px 0; font-size: 15px; color: #333; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #eee; padding-bottom: 8px; }
        input { width: 100%; padding: 10px; margin: 6px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #2e7d32; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .copy-btn { cursor: pointer; background: #1b5e20; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .copy-btn:hover { background: #123d15; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f1f8e9; color: #1b5e20; }
    </style>
    <script>
        function copyNumber(num) {
            navigator.clipboard.writeText(num);
            alert("নম্বরটি সফলভাবে কপি হয়েছে: " + num);
        }
    </script>
</head>
<body>
<div class="sidebar">
    <h3>স্মার্ট সেবা</h3>
    <a href="dashboard.php">ড্যাশবোর্ড</a>
    <a href="recharge.php">রিচার্জ</a>
    <a href="logout.php" style="background:#c62828; margin-top:20px; text-align:center; border-radius: 4px;">লগআউট</a>
</div>
<div class="main">
    <div class="card">
        <h2>পার্সোনাল অ্যাকাউন্টে সেন্ড মানি করুন</h2>
        <div class="pay-box">
            <p><span><b>বিকাশ পার্সোনাল:</b> 01781861215</span> <button class="copy-btn" onclick="copyNumber('01781861215')">কপি করুন</button></p>
            <p><span><b>নগদ পার্সোনাল:</b> 01781861215</span> <button class="copy-btn" onclick="copyNumber('01781861215')">কপি করুন</button></p>
            <p><span><b>উপায় (Upay):</b> 01781861215</span> <button class="copy-btn" onclick="copyNumber('01781861215')">কপি করুন</button></p>
            <p><span><b>এমক্যাশ (mCash):</b> 01601861219</span> <button class="copy-btn" onclick="copyNumber('01601861219')">কপি করুন</button></p>
            <p style="border-bottom: none;"><span><b>রকেট (Rocket):</b> 01601861219</span> <button class="copy-btn" onclick="copyNumber('01601861219')">কপি করুন</button></p>
        </div>

        <h2>রিচার্জ রিকোয়েস্ট পাঠান</h2>
        <p style="color:#d32f2f; font-size:14px;">* সর্বনিম্ন রিচার্জ ১০০ টাকা হতে হবে।</p>
        <form method="POST">
            <label>টাকার পরিমাণ:</label>
            <input type="number" name="amount" min="100" placeholder="যেমন: ১০০ বা তার বেশি" required>
            <label>ট্রানজেকশন আইডি (TrxID):</label>
            <input type="text" name="trxid" placeholder="পেমেন্ট করার পর ট্রানজেকশন আইডি এখানে লিখুন" required>
            <button type="submit" name="submit_recharge">রিচার্জ রিকোয়েস্ট পাঠান</button>
        </form>
    </div>

    <div class="card">
        <h2>আপনার রিচার্জ হিস্টরি</h2>
        <table>
            <tr><th>টাকা</th><th>ট্রানজেকশন আইডি</th><th>স্ট্যাটাস</th></tr>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM deposits WHERE user_id='$user_id' ORDER BY id DESC");
            while($row = mysqli_fetch_assoc($q)){
                echo "<tr><td>৳ ".$row['amount']."</td><td>".$row['trxid']."</td><td>".$row['status']."</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>