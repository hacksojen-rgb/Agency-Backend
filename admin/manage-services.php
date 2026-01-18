<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
require_once '../db.php';

$action = $_GET['action'] ?? 'list';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon = $_POST['icon'];
    $features = json_encode(array_filter(array_map('trim', explode("\n", $_POST['features']))));

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE services SET title=?, description=?, icon=?, features=? WHERE id=?");
        $stmt->execute([$title, $description, $icon, $features, $_POST['id']]);
        $msg = "Service updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO services (title, description, icon, features) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $description, $icon, $features]);
        $msg = "Service added successfully!";
    }
}

if ($action == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: manage-services.php?msg=Deleted");
    exit;
}

$editData = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editData = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex">
    <!-- Reuse Sidebar Logic -->
    <div class="w-64 bg-[#014034] text-white flex flex-col h-screen fixed">
        <div class="p-8 text-2xl font-black border-b border-white/10">Build to Grow</div>
        <nav class="flex-grow p-4 space-y-2 mt-4">
            <a href="dashboard.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
            <a href="manage-services.php" class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl font-bold"><i class="fas fa-concierge-bell"></i><span>Services</span></a>
            <a href="manage-portfolio.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-briefcase"></i><span>Portfolio</span></a>
            <a href="settings.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-cog"></i><span>Settings</span></a>
        </nav>
    </div>

    <div class="flex-grow ml-64 min-h-screen">
        <header class="bg-white shadow-sm p-6 flex justify-between items-center sticky top-0 z-10">
            <h2 class="text-xl font-bold text-gray-800">Manage Growth Services</h2>
            <a href="manage-services.php?action=add" class="bg-[#014034] text-white px-6 py-2 rounded-xl font-bold">Add New</a>
        </header>

        <main class="p-10">
            <?php if ($msg || isset($_GET['msg'])): ?>
                <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 font-bold border border-green-100">Operation Successful</div>
            <?php endif; ?>

            <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 mb-10 max-w-3xl">
                <h3 class="text-2xl font-bold text-[#014034] mb-8"><?php echo $editData ? 'Edit Service' : 'Create New Service'; ?></h3>
                <form method="POST" class="space-y-6">
                    <?php if ($editData): ?><input type="hidden" name="id" value="<?php echo $editData['id']; ?>"><?php endif; ?>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Service Title</label>
                        <input type="text" name="title" required value="<?php echo $editData['title'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Short Description</label>
                        <textarea name="description" rows="3" required class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]"><?php echo $editData['description'] ?? ''; ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Icon Name (Lucide React Name, e.g., Code2, Target)</label>
                        <input type="text" name="icon" required value="<?php echo $editData['icon'] ?? 'Target'; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Features (One per line)</label>
                        <textarea name="features" rows="6" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]" placeholder="Feature 1&#10;Feature 2&#10;Feature 3"><?php 
                            if ($editData && $editData['features']) {
                                $featArr = json_decode($editData['features'], true);
                                echo is_array($featArr) ? implode("\n", $featArr) : '';
                            }
                        ?></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-[#014034] text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">Save Service</button>
                        <a href="manage-services.php" class="bg-gray-100 text-gray-600 px-8 py-3 rounded-xl font-bold">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-6 font-bold text-gray-600">ID</th>
                            <th class="p-6 font-bold text-gray-600">Title</th>
                            <th class="p-6 font-bold text-gray-600">Description</th>
                            <th class="p-6 font-bold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($services as $s): ?>
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-6 text-gray-400 font-mono"><?php echo $s['id']; ?></td>
                            <td class="p-6 font-bold text-[#014034]"><?php echo $s['title']; ?></td>
                            <td class="p-6 text-gray-500 text-sm max-w-md"><?php echo $s['description']; ?></td>
                            <td class="p-6 space-x-3">
                                <a href="manage-services.php?action=edit&id=<?php echo $s['id']; ?>" class="text-blue-600 font-bold hover:underline">Edit</a>
                                <a href="manage-services.php?action=delete&id=<?php echo $s['id']; ?>" onclick="return confirm('Are you sure?')" class="text-red-600 font-bold hover:underline">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>