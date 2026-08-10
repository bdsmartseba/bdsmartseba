<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$service_id = intval($_GET['id']);

$s_q = mysqli_query($conn, "SELECT * FROM services WHERE id='$service_id'");
$service = mysqli_fetch_assoc($s_q);

if (!$service) {
    echo "<script>alert('সেবাটি পাওয়া যায়নি!'); window.location='dashboard.php';</script>";
    exit();
}

// ইউজারের বর্তমান ব্যালেন্স চেক করা
$u_q = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($u_q);

if (isset($_POST['place_order'])) {
    $user_data = mysqli_real_escape_string($conn, $_POST['user_data']);
    $s_name = $service['service_name'];
    $s_price = $service['price'];

    // ব্যালেন্স পর্যাপ্ত আছে কিনা যাচাই
    if ($user['balance'] < $s_price) {
        echo "<script>alert('সতর্কবার্তা: আপনার অ্যাকাউন্টে পর্যাপ্ত পরিমাণ ব্যালেন্স নেই! দয়া করে আগে আপনার অ্যাকাউন্টে ব্যালেন্স চেক করুন এবং রিচার্জ করুন, তারপরে অর্ডার কনফার্ম করুন।'); window.location='recharge.php';</script>";
        exit();
    } else {
        // ব্যালেন্স কেটে নেওয়া
        mysqli_query($conn, "UPDATE users SET balance = balance - $s_price WHERE id='$user_id'");
        
        // অর্ডার সেভ করা (আপনার ডাটাবেজ টেবিলের কলাম অনুযায়ী ঠিক করা হয়েছে)
        $insert = mysqli_query($conn, "INSERT INTO orders (user_id, service_name, price, user_data, status) VALUES ('$user_id', '$s_name', '$s_price', '$user_data', 'Pending')");
        
        if($insert) {
            echo "<script>alert('অর্ডার সফলভাবে সম্পন্ন হয়েছে!'); window.location='dashboard.php';</script>";
        } else {
            echo "<script>alert('দুঃখিত, অর্ডারটি সম্পন্ন করতে সমস্যা হয়েছে।');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>অর্ডার কনফার্ম করুন - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; background: #e8f5e9; padding: 40px; }
        .card { background: white; max-width: 500px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .alert-box { background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; border: 1px solid #ffcdd2; }
        textarea, button { width: 100%; padding: 10px; margin-top: 10px; border-radius: 4px; box-sizing: border-box; border: 1px solid #ccc; }
        button { background: #2e7d32; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #1b5e20; }
    </style>
</head>
<body>
<div class="card">
    <h2>অর্ডার কনফার্ম করুন</h2>
    <hr style="border:0; border-top:1px solid #eee; margin-bottom:15px;">
    
    <p><b>সেবা:</b> <?php echo $service['service_name']; ?></p>
    <p><b>মূল্য:</b> ৳ <?php echo $service['price']; ?></p>
    <p><b>আপনার বর্তমান ব্যালেন্স:</b> ৳ <?php echo $user['balance']; ?></p>
    <p><b>প্রয়োজনীয় তথ্য নির্দেশিকা:</b> <?php echo $service['requirements']; ?></p>

    <?php if ($user['balance'] < $service['price']): ?>
        <div class="alert-box">
            <b>সতর্কবার্তা:</b> আপনার অ্যাকাউন্টে পর্যাপ্ত পরিমাণ ব্যালেন্স নেই! দয়া করে আগে আপনার অ্যাকাউন্টে ব্যালেন্স রিচার্জ করুন, তারপরে অর্ডার কনফার্ম করুন।
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <label><b>আপনার তথ্য এখানে লিখুন:</b></label>
        <textarea name="user_data" rows="4" placeholder="প্রয়োজনীয় তথ্য প্রদান করুন..." required></textarea>
        
        <?php if ($user['balance'] >= $service['price']): ?>
            <button type="submit" name="place_order">অর্ডার নিশ্চিত করুন</button>
        <?php else: ?>
            <a href="recharge.php" style="display:block; background:#c62828; color:white; text-align:center; padding:10px; text-decoration:none; border-radius:4px; font-weight:bold; margin-top:10px;">বিকাশ রিচার্জ করুন</a>
        <?php endif; ?>
    </form>
    <br>
    <a href="dashboard.php" style="color: #1b5e20; text-decoration: none;">&larr; ড্যাশবোর্ডে ফিরে যান</a>
</div>
</body>
</html>