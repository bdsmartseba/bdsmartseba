<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>লগইন - স্মার্ট সেবা</title>
    <style>
        body { background-color: #e8f5e9; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-card { background: #ffffff; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,128,0,0.2); width: 350px; border-top: 5px solid #2e7d32; }
        h2 { color: #2e7d32; text-align: center; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #c8e6c9; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #2e7d32; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #1b5e20; }
        a { color: #2e7d32; text-decoration: none; display: block; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>লগইন করুন</h2>
        <form action="login_process.php" method="POST">
            <input type="email" name="email" placeholder="আপনার জিমেইল (Gmail)" required>
            <input type="password" name="password" placeholder="পাসওয়ার্ড" required>
            <button type="submit">লগইন</button>
        </form>
        <a href="register.php">অ্যাকাউন্ট নেই? রেজিস্ট্রেশন করুন</a>
    </div>
</body>
</html>