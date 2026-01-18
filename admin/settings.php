<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
require_once '../db.php';

$msg = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $companyName = $_POST['companyName'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $aboutTitle = $_POST['aboutTitle'];
    $aboutText = $_POST['aboutText'];

    // Check if settings exist
    $exists = $pdo->query("SELECT COUNT(*) FROM site_settings")->fetchColumn();
    if ($exists > 0) {
        $stmt = $pdo->prepare("UPDATE site_settings SET companyName=?, address=?, phone=?, email=?, aboutTitle=?, aboutText=? LIMIT 1");
        $stmt->execute([$companyName, $address, $phone, $email, $aboutTitle, $aboutText]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO site_settings (companyName, address, phone, email, aboutTitle, aboutText) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$companyName, $address, $phone, $email, $aboutTitle, $aboutText]);
    }
    $msg = "Settings updated successfully!";
}

$settings = $pdo->query("SELECT * FROM site_settings LIMIT 1")->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Site Settings | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex">
    <div class="w-64 bg-[#014034] text-white flex flex-col h-screen fixed">
        <div class="p-8 text-2xl font-black border-b border-white/10">Build to Grow</div>
        <nav class="flex-grow p-4 space-y-2 mt-4">
            <a href="dashboard.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
            <a href="manage-services.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-concierge-bell"></i><span>Services</span></a>
            <a href="manage-portfolio.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-briefcase"></i><span>Portfolio</span></a>
            <a href="settings.php" class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl font-bold"><i class="fas fa-cog"></i><span>Settings</span></a>
        </nav>
    </div>

    <div class="flex-grow ml-64 min-h-screen">
        <header class="bg-white shadow-sm p-6 sticky top-0 z-10">
            <h2 class="text-xl font-bold text-gray-800">General Site Settings</h2>
        </header>

        <main class="p-10">
            <?php if ($msg): ?><div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 font-bold"><?php echo $msg; ?></div><?php endif; ?>

            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 max-w-4xl">
                <form method="POST" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Agency Name</label>
                            <input type="text" name="companyName" value="<?php echo $settings['companyName'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contact Email</label>
                            <input type="email" name="email" value="<?php echo $settings['email'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" name="phone" value="<?php echo $settings['phone'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Office Address</label>
                            <input type="text" name="address" value="<?php echo $settings['address'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none">
                        </div>
                    </div>
                    <hr class="border-gray-100">
                    <h4 class="text-lg font-bold text-[#014034]">About Section Content</h4>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">About Section Title</label>
                        <input type="text" name="aboutTitle" value="<?php echo $settings['aboutTitle'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">About Section Text</label>
                        <textarea name="aboutText" rows="5" class="w-full px-5 py-3 rounded-xl border outline-none"><?php echo $settings['aboutText'] ?? ''; ?></textarea>
                    </div>
                    <button type="submit" class="bg-[#014034] text-white px-10 py-4 rounded-xl font-bold shadow-lg">Save All Settings</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>