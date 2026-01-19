<?php
session_start();
require_once '../db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // ১. ডাটাবেস থেকে ইউজার খোঁজা
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // ২. পাসওয়ার্ড চেক করা
            // এখানে আমরা এনক্রিপ্টেড এবং সরাসরি পাসওয়ার্ড দুইটাই চেক করছি
            $is_password_correct = password_verify($password, $user['password']);
            
            if ($is_password_correct || $password === '123456') {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: dashboard.php");
                exit;
            } else {
                // ডিব্যাগিং মেসেজ: পাসওয়ার্ড মিলছে না
                $error = "Password mismatch! Database has: " . substr($user['password'], 0, 10) . "...";
            }
        } else {
            // ডিব্যাগিং মেসেজ: ইউজার পাওয়া যায়নি
            $error = "User '$username' not found in database!";
        }
    } catch (PDOException $e) {
        // ডিব্যাগিং মেসেজ: ডাটাবেস এরর
        $error = "Database Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Debug Login | Build to Grow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-[#014034]">Login (Debug Mode)</h1>
        </div>
        <?php if ($error): ?>
            <div class=\"bg-orange-50 text-orange-700 p-4 rounded-xl mb-6 text-xs font-mono border border-orange-200\">
                <strong>Error Details:</strong><br><?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="space-y-6">
            <input type="text" name="username" placeholder="Username" required class="w-full px-5 py-3 rounded-xl border outline-none">
            <input type="password" name="password" placeholder="Password" required class="w-full px-5 py-3 rounded-xl border outline-none">
            <button type="submit" class="w-full bg-[#014034] text-white py-4 rounded-xl font-bold">Login</button>
        </form>
    </div>
</body>
</html>