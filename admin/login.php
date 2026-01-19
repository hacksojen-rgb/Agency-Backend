<?php
session_start();
require_once '../db.php';

// ১. অলরেডি লগইন থাকলে ড্যাশবোর্ডে পাঠিয়ে দাও
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // ২. ডাটাবেস থেকে ইউজার খোঁজা
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // ৩. পাসওয়ার্ড চেক (এনক্রিপশন এবং সরাসরি ১২৩৪৫৬ দুইটাই সাপোর্ট করবে)
        if ($user) {
            if (password_verify($password, $user['password']) || $password === '123456') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Invalid username or password";
        }
    } catch (PDOException $e) {
        $error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Build to Grow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-[#014034]">Admin Login</h1>
            <p class="text-gray-500">Access your growth agency dashboard</p>
        </div>
        
        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Username</label>
                <input type="text" name="username" required class="w-full px-5 py-3 rounded-xl border focus:border-[#014034] outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required class="w-full px-5 py-3 rounded-xl border focus:border-[#014034] outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-[#014034] text-white py-4 rounded-xl font-bold hover:bg-[#00332a] shadow-lg transition-all">
                Login
            </button>
        </form>
    </div>
</body>
</html>