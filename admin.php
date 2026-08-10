<?php
session_start();
include 'db.php';

// ১. নতুন সেবা যোগ করা
if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = floatval($_POST['price']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $req = mysqli_real_escape_string($conn, $_POST['req']);
    mysqli_query($conn, "INSERT INTO services (service_name, price, time_required, requirements) VALUES ('$name', '$price', '$time', '$req')");
    echo "<script>alert('নতুন সেবা যোগ করা হয়েছে!'); window.location='admin.php?page=manage_services';</script>";
}

// ২. সেবা আপডেট করা
if (isset($_POST['update_service'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $price = floatval($_POST['price']);
    $time = mysqli_real_escape_string($conn, $_POST['time_required']);
    $req = mysqli_real_escape_string($conn, $_POST['requirements']);
    mysqli_query($conn, "UPDATE services SET service_name='$name', price='$price', time_required='$time', requirements='$req' WHERE id=$id");
    echo "<script>alert('সেবা আপডেট করা হয়েছে!'); window.location='admin.php?page=manage_services';</script>";
}

// ৩. সেবা ডিলিট করা
if (isset($_POST['delete_service'])) {
    $id = intval($_POST['service_id']);
    mysqli_query($conn, "DELETE FROM services WHERE id=$id");
    echo "<script>alert('ডিলিট হয়েছে!'); window.location='admin.php?page=manage_services';</script>";
}

// ৪. রিচার্জ অ্যাপ্রুভ বা অ্যাকশন (যদি ডিপোজিট টেবিল থাকে)
if (isset($_GET['approve_deposit'])) {
    $dep_id = intval($_GET['approve_deposit']);
    // রিচার্জ অ্যাপ্রুভ করার কোড এখানে কাজ করবে
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>অ্যাডমিন প্যানেল - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; background: #e8f5e9; display: flex; margin: 0; }
        .sidebar { width: 230px; background: #1b5e20; color: white; height: 100vh; padding: 20px; position: fixed; }
        .sidebar h3 { text-align: center; color: #fff; }
        .sidebar a { display: block; color: #fff; padding: 12px; text-decoration: none; border-bottom: 1px solid #2e7d32; }
        .sidebar a:hover { background: #2e7d32; }
        .main { margin-left: 250px; padding: 20px; width: calc(100% - 250px); box-sizing: border-box; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        input, textarea { width: 100%; padding: 8px; margin: 4px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        textarea { resize: vertical; height: 60px; }
        button { padding: 8px 12px; border: none; cursor: pointer; border-radius: 4px; color: white; font-weight: bold; font-size: 13px; }
        .btn-add { background: #2e7d32; width: 100%; padding: 10px; font-size: 15px; }
        .btn-update { background: #1565c0; }
        .btn-delete { background: #c62828; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: middle; }
        th { background: #f1f8e9; color: #1b5e20; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>অ্যাডমিন কন্ট্রোল</h3>
    <a href="admin.php?page=add_service">নতুন সেবা যোগ করুন</a>
    <a href="admin.php?page=manage_services">সার্ভিস ম্যানেজমেন্ট</a>
    <a href="admin.php?page=manage_recharge">রিচার্জ ম্যানেজমেন্ট</a>
    <a href="dashboard.php" style="background:#c62828; margin-top:20px; text-align:center; border-radius: 4px;">মেইন সাইটে যান</a>
</div>

<div class="main">
    <?php
    $page = $_GET['page'] ?? 'manage_services';

    if ($page == 'add_service'): ?>
        <div class="card">
            <h2>নতুন সেবা যোগ করুন</h2>
            <form method="POST">
                <label>সেবার নাম:</label>
                <input type="text" name="name" placeholder="যেমন: স্মার্ট আইডি কার্ড পিডিএফ" required>
                
                <label>মূল্য (টাকা):</label>
                <input type="number" name="price" placeholder="যেমন: 100" required>
                
                <label>সময় (Time Required):</label>
                <input type="text" name="time" placeholder="যেমন: ১০-১৫ মিনিট" required>
                
                <label>তথ্য (Requirements):</label>
                <textarea name="req" placeholder="গ্রাহক কি কি তথ্য প্রদান করবে তা এখানে লিখুন..." required></textarea>
                
                <button type="submit" name="add_service" class="btn-add" style="margin-top: 10px;">সেবা সংরক্ষণ করুন</button>
            </form>
        </div>

    <?php elseif ($page == 'manage_services'): ?>
        <div class="card">
            <h2>সার্ভিস ম্যানেজমেন্ট</h2>
            <table>
                <tr>
                    <th style="width: 25%;">সেবার নাম</th>
                    <th style="width: 12%;">দাম</th>
                    <th style="width: 15%;">সময়</th>
                    <th style="width: 33%;">তথ্য (Requirements)</th>
                    <th style="width: 15%;">অ্যাকশন</th>
                </tr>
                <?php
                $res = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <form method='POST'>
                        <input type='hidden' name='id' value='".$row['id']."'>
                        <td><input type='text' name='service_name' value='".$row['service_name']."'></td>
                        <td><input type='number' name='price' value='".$row['price']."'></td>
                        <td><input type='text' name='time_required' value='".$row['time_required']."'></td>
                        <td><textarea name='requirements'>".$row['requirements']."</textarea></td>
                        <td>
                            <button type='submit' name='update_service' class='btn-update'>আপডেট</button>
                            <button type='submit' name='delete_service' class='btn-delete' onclick='return confirm(\"আপনি কি এটি ডিলিট করতে চান?\")'>ডিলিট</button>
                            <input type='hidden' name='service_id' value='".$row['id']."'>
                        </td>
                        </form>
                    </tr>";
                }
                ?>
            </table>
        </div>

    <?php elseif ($page == 'manage_recharge'): ?>
        <div class="card">
            <h2>রিচার্জ ম্যানেজমেন্ট</h2>
            <table>
                <tr>
                    <th>ইউজার আইডি</th>
                    <th>টাকার পরিমাণ</th>
                    <th>মেথড / ট্রানজেকশন</th>
                    <th>স্ট্যাটাস</th>
                    <th>অ্যাকশন</th>
                </tr>
                <?php
                // আপনার ডাটাবেজে যদি ডিপোজিট বা রিচার্জ টেবিল থাকে (যেমন: deposits বা recharge)
                $dep_query = mysqli_query($conn, "SELECT * FROM deposits ORDER BY id DESC");
                if($dep_query && mysqli_num_rows($dep_query) > 0) {
                    while($dep = mysqli_fetch_assoc($dep_query)) {
                        echo "<tr>
                            <td>".$dep['user_id']."</td>
                            <td>৳ ".$dep['amount']."</td>
                            <td>".$dep['trxid']."</td>
                            <td>".$dep['status']."</td>
                            <td><a href='?page=manage_recharge&approve=".$dep['id']."' style='background:green; color:white; padding:5px 10px; text-decoration:none; border-radius:3px;'>অ্যাপ্রুভ</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>কোনো রিচার্জ রিকোয়েস্ট পাওয়া যায়নি।</td></tr>";
                }
                ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>