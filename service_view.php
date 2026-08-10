<?php
session_start();
include 'db.php';

// সার্ভিস আইডি চেক করা
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = mysqli_query($conn, "SELECT * FROM services WHERE id = $service_id");
$service = mysqli_fetch_assoc($query);

if (!$service) {
    echo "সেবাটি পাওয়া যায়নি!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title><?php echo $service['service_name']; ?> - স্মার্ট সেবা</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f8; padding: 20px; }
        .service-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); max-width: 600px; margin: auto; }
        h1 { color: #2e7d32; }
        .price { font-size: 20px; font-weight: bold; color: #d32f2f; margin: 10px 0; }
        .info-box { background: #e8f5e9; padding: 15px; border-radius: 8px; margin: 20px 0; border-left: 5px solid #2e7d32; }
        .btn-order { display: block; background: #2e7d32; color: white; text-align: center; padding: 15px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .btn-order:hover { background: #1b5e20; }
    </style>
</head>
<body>

<div class="service-card">
    <h1><?php echo $service['service_name']; ?></h1>
    <div class="price">মূল্য: ৳ <?php echo $service['price']; ?></div>
    
    <div class="info-box">
        <p><strong>ডেলিভারি সময়:</strong> <?php echo $service['time_required']; ?></p>
        <p><strong>প্রয়োজনীয় তথ্য:</strong><br> <?php echo nl2br($service['requirements']); ?></p>
    </div>

    <a href="order.php?id=<?php echo $service['id']; ?>" class="btn-order">অর্ডার করুন</a>
    <a href="dashboard.php" style="display:block; text-align:center; margin-top:15px; color:#555;">ফিরে যান</a>
</div>

</body>
</html>