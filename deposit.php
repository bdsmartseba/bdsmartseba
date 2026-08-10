<?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>রিচার্জ - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; margin: 0; background-color: #e8f5e9; display: flex; }
        .sidebar { width: 250px; background-color: #2e7d32; color: white; height: 100vh; padding: 20px; position: fixed; box-sizing: border-box; overflow-y: auto; }
        .sidebar h2 { color: white; font-size: 20px; text-align: center; margin-bottom: 20px; }
        .sidebar a { display: block; color: white; padding: 10px; text-decoration: none; border-bottom: 1px solid #1b5e20; border-radius: 5px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover { background-color: #1b5e20; }
        .main-content { margin-left: 250px; padding: 30px; width: calc(100% - 250px); box-sizing: border-box; }
        
        .form-card, .history-card { background: white; padding: 25px; border-radius: 10px; width: 100%; max-width: 650px; box-shadow: 0 4px 10px rgba(0,128,0,0.1); border-top: 5px solid #2e7d32; margin-bottom: 30px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #c8e6c9; border-radius: 5px; box-sizing: border-box; }
        button.submit-btn { background-color: #2e7d32; color: white; padding: 12px; width: 100%; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button.submit-btn:hover { background-color: #1b5e20; }
        
        .bkash-box { background: #e8f5e9; padding: 15px; border-radius: 5px; color: #2e7d32; font-weight: bold; display: flex; justify-content: space-between; align-items: center; border: 1px dashed #2e7d32; margin-bottom: 15px; }
        .copy-btn { background-color: #2e7d32; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 14px; }
        .copy-btn:hover { background-color: #1b5e20; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #c8e6c9; padding: 10px; text-align: center; font-size: 14px; }
        th { background-color: #2e7d32; color: white; }
        .pending { color: #d35400; font-weight: bold; background: #fef5e7; padding: 4px 8px; border-radius: 4px; display: inline-block; }
        .approved { color: #27ae60; font-weight: bold; background: #eafaf1; padding: 4px 8px; border-radius: 4px; display: inline-block; }
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
        <h2>বিকাশ রিচার্জ করুন</h2>
        
        <div class="form-card">
            <form action="deposit_process.php" method="POST">
                <div class="bkash-box">
                    <span>বিকাশ পার্সোনাল: <span id="bkashNum">01781861215</span> (Send Money)</span>
                    <button type="button" class="copy-btn" onclick="copyNumber()">নম্বর কপি করুন</button>
                </div>
                <input type="number" name="amount" placeholder="পরিমাণ (সর্বনিম্ন ১০০ টাকা)" min="100" required>
                <input type="text" name="sender_number" placeholder="যে নাম্বার থেকে পাঠিয়েছেন" required>
                <input type="text" name="txid" placeholder="ট্রানজেকশন আইডি (TrxID)" required>
                <button type="submit" class="submit-btn">রিচার্জ রিকোয়েস্ট জমা দিন</button>
            </form>
        </div>

        <div class="history-card">
            <h3>আপনার রিচার্জের ইতিহাস ও স্ট্যাটাস</h3>
            <table>
                <tr>
                    <th>তারিখ ও সময়</th>
                    <th>টাকা</th>
                    <th>বিকাশ নাম্বার</th>
                    <th>ট্রানজেকশন আইডি</th>
                    <th>স্ট্যাটাস</th>
                </tr>
                <?php
                $query = "SELECT * FROM deposits WHERE user_id = $user_id ORDER BY id DESC";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_text = ($row['status'] == 'Approved') ? 'এপ্রুভ হয়েছে' : 'পেন্ডিং আছে';
                        $status_class = ($row['status'] == 'Approved') ? 'approved' : 'pending';
                        
                        echo "<tr>
                                <td>".$row['created_at']."</td>
                                <td>৳ ".$row['amount']."</td>
                                <td>".$row['sender_number']."</td>
                                <td>".$row['txid']."</td>
                                <td><span class='".$status_class."'>".$status_text."</span></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>কোনো রিচার্জ হিস্ট্রি পাওয়া যায়নি।</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>

    <script>
        function copyNumber() {
            var numText = document.getElementById("bkashNum").innerText;
            navigator.clipboard.writeText(numText);
            alert("বিকাশ নম্বরটি সফলভাবে কপি হয়েছে: " + numText);
        }
    </script>

</body>
</html>