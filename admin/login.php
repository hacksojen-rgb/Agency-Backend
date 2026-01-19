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

    // ডাটাবেস থেকে ইউজার চেক
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // পাসওয়ার্ড এনক্রিপশন চেক ছাড়াই সরাসরি চেক (শুধুমাত্র ড্যাশবোর্ডে ঢোকার জন্য)
    if ($user && ($password === '123456' || password_verify($password, $user['password']))) {
        $_SESSION['admin_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>