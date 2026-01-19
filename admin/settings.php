<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$success = "";
$error = "";

// ডাটাবেস থেকে বর্তমান সেটিংস নিয়ে আসা
$stmt = $pdo->query("SELECT * FROM site_settings LIMIT 1");
$settings = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $sql = "UPDATE site_settings SET 
                company_name = ?, address = ?, phone = ?, email = ?, 
                about_title = ?, about_text = ?, logo_url = ?, 
                facebook_url = ?, twitter_url = ?, linkedin_url = ? 
                WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $_POST['company_name'], $_POST['address'], $_POST['phone'], $_POST['email'],
            $_POST['about_title'], $_POST['about_text'], $_POST['logo_url'],
            $_POST['facebook_url'], $_POST['twitter_url'], $_POST['linkedin_url'],
            $settings['id']
        ]);
        $success = "Settings updated successfully!";
        // আপডেট হওয়ার পর নতুন ডাটা রিফ্রেশ করা
        $stmt = $pdo->query("SELECT * FROM site_settings LIMIT 1");
        $settings = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Update failed: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Global Settings | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow">
        <h1 class="text-2xl font-bold mb-6">Global Site Settings</h1>
        <?php if($success) echo "<p class='text-green-600 mb-4'>$success</p>"; ?>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold">Agency Name</label>
                <input type="text" name="company_name" value="<?php echo $settings['company_name']; ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold">Logo URL</label>
                <input type="text" name="logo_url" value="<?php echo $settings['logo_url']; ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold">Contact Email</label>
                <input type="email" name="email" value="<?php echo $settings['email']; ?>" class="w-full border p-2 rounded">
            </div>
            <div>
                <label class="block font-bold">Contact Phone</label>
                <input type="text" name="phone" value="<?php echo $settings['phone']; ?>" class="w-full border p-2 rounded">
            </div>
            <div class="md:col-span-2">
                <label class="block font-bold">Office Address</label>
                <textarea name="address" class="w-full border p-2 rounded"><?php echo $settings['address']; ?></textarea>
            </div>
            <div class="md:col-span-2 border-t pt-4 mt-4">
                <h3 class="font-bold text-lg mb-2">Social Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="facebook_url" placeholder="Facebook URL" value="<?php echo $settings['facebook_url']; ?>" class="border p-2 rounded">
                    <input type="text" name="twitter_url" placeholder="Twitter URL" value="<?php echo $settings['twitter_url']; ?>" class="border p-2 rounded">
                    <input type="text" name="linkedin_url" placeholder="LinkedIn URL" value="<?php echo $settings['linkedin_url']; ?>" class="border p-2 rounded">
                </div>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Save All Settings</button>
        </form>
    </div>
</body>
</html>