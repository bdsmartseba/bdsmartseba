<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$user_q = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($user_q);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>কাস্টমার ড্যাশবোর্ড - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; background: #e8f5e9; display: flex; margin: 0; }
        .sidebar { width: 230px; background: #1b5e20; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h3 { text-align: center; color: #fff; }
        .sidebar a { display: block; color: #fff; padding: 12px; text-decoration: none; border-bottom: 1px solid #2e7d32; }
        .sidebar a:hover { background: #2e7d32; }
        .main { margin-left: 250px; padding: 20px; width: calc(100% - 250px); box-sizing: border-box; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat-box { background: #2e7d32; color: white; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f1f8e9; color: #1b5e20; }
    </style>
</head>
<body>
<div class="sidebar">
    <h3>স্মার্ট সেবা</h3>
    <a href="dashboard.php">ড্যাশবোর্ড</a>
    <a href="recharge.php">বিকাশ রিচার্জ</a>
    <a href="logout.php" style="background:#c62828; margin-top:20px; text-align:center; border-radius: 4px;">লগআউট</a>
</div>
<div class="main">
    <div class="stats">
        <div class="stat-box">
            <h3>টোটাল ব্যালেন্স</h3>
            <h2>৳ <?php echo isset($user['balance']) ? $user['balance'] : '0.00'; ?></h2>
        </div>
        <div class="stat-box" style="background: #1565c0;">
            <h3>ইউজার নাম</h3>
            <h2><?php echo isset($user['name']) ? $user['name'] : 'Customer'; ?></h2>
        </div>
    </div>

    <div class="card">
        <h2>উপলব্ধ সেবাসমূহ</h2>
        <table>
            <tr><th>সেবার নাম</th><th>মূল্য</th><th>সময়</th><th>প্রয়োজনীয় তথ্য</th><th>অ্যাকশন</th></tr>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                    <td><b>".$row['service_name']."</b></td>
                    <td>৳ ".$row['price']."</td>
                    <td>".$row['time_required']."</td>
                    <td>".$row['requirements']."</td>
                    <td><a href='order.php?id=".$row['id']."' style='background:#2e7d32; color:white; padding:6px 12px; text-decoration:none; border-radius:4px;'>অর্ডার করুন</a></td>
                </tr>";
            }
            ?>
        </table>
    </div>

    <div class="card">
        <h2>আপনার অর্ডার হিস্টরি</h2>
        <table>
            <tr><th>সেবার নাম</th><th>মূল্য</th><th>স্ট্যাটাস</th><th>তারিখ</th></tr>
            <?php
            $ord_q = mysqli_query($conn, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
            if($ord_q && mysqli_num_rows($ord_q) > 0) {
                while($ord = mysqli_fetch_assoc($ord_q)){
                    echo "<tr><td>".$ord['service_name']."</td><td>৳ ".$ord['price']."</td><td>".$ord['status']."</td><td>".$ord['created_at']."</td></tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>কোনো অর্ডার পাওয়া যায়নি।</td></tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>