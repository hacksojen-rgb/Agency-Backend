<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
require_once '../db.php';

$servicesCount = $pdo->query("SELECT count(*) FROM services")->fetchColumn();
$portfolioCount = $pdo->query("SELECT count(*) FROM portfolio")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Build to Grow Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-[#014034] text-white flex flex-col">
            <div class="p-8 text-2xl font-black border-b border-white/10">Build to Grow</div>
            <nav class="flex-grow p-4 space-y-2 mt-4">
                <a href="dashboard.php" class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl font-bold"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
                <a href="manage-services.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl transition-all"><i class="fas fa-concierge-bell"></i><span>Services</span></a>
                <a href="manage-portfolio.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl transition-all"><i class="fas fa-briefcase"></i><span>Portfolio</span></a>
                <a href="settings.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl transition-all"><i class="fas fa-cog"></i><span>Settings</span></a>
            </nav>
            <div class="p-4 border-t border-white/10">
                <a href="logout.php" class="flex items-center space-x-3 p-3 text-red-300 hover:text-white transition-all font-bold"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
            </div>
        </div>

        <!-- Content -->
        <div class="flex-grow overflow-y-auto">
            <header class="bg-white shadow-sm p-6 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Admin Dashboard Overview</h2>
                <div class="text-sm font-medium text-gray-500">Welcome, Administrator</div>
            </header>
            <main class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="text-gray-400 text-sm font-bold uppercase mb-2">Total Services</div>
                        <div class="text-4xl font-black text-[#014034]"><?php echo $servicesCount; ?></div>
                    </div>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="text-gray-400 text-sm font-bold uppercase mb-2">Portfolio Projects</div>
                        <div class="text-4xl font-black text-[#014034]"><?php echo $portfolioCount; ?></div>
                    </div>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <div class="text-gray-400 text-sm font-bold uppercase mb-2">Site Status</div>
                        <div class="text-4xl font-black text-green-500">Online</div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-bold mb-6">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="manage-services.php?action=add" class="px-6 py-3 bg-[#014034] text-white rounded-xl font-bold hover:shadow-lg transition-all">Add New Service</a>
                        <a href="manage-portfolio.php?action=add" class="px-6 py-3 bg-white border border-[#014034] text-[#014034] rounded-xl font-bold hover:bg-gray-50 transition-all">Upload Project</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>