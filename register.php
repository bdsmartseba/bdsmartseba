<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>রেজিস্ট্রেশন - স্মার্ট সেবা</title>
    <style>
        body { background-color: #e8f5e9; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .reg-card { background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,128,0,0.2); width: 350px; border-top: 5px solid #2e7d32; }
        h2 { color: #2e7d32; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #c8e6c9; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #2e7d32; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #1b5e20; }
        a { color: #2e7d32; text-decoration: none; display: block; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="reg-card">
        <h2>রেজিস্ট্রেশন</h2>
        <form action="register_process.php" method="POST">
            <input type="text" name="name" placeholder="পুরো নাম" required>
            <input type="text" name="mobile" placeholder="সচল মোবাইল নাম্বার" required>
            <input type="email" name="email" placeholder="ইমেইল (Gmail)" required>
            <input type="password" name="password" placeholder="পাসওয়ার্ড (৮ সংখ্যা)" minlength="8" required>
            <input type="password" name="cpassword" placeholder="কনফার্ম পাসওয়ার্ড" required>
            <button type="submit">রেজিস্ট্রেশন করুন</button>
        </form>
        <a href="login.php">ইতিমধ্যে অ্যাকাউন্ট আছে? লগইন করুন</a>
    </div>
</body>
</html>