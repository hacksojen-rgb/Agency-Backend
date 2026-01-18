<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
require_once '../db.php';

$action = $_GET['action'] ?? 'list';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $imageUrl = $_POST['imageUrl'];
    $client = $_POST['client'];
    $content = $_POST['content'];

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $stmt = $pdo->prepare("UPDATE portfolio SET title=?, category=?, imageUrl=?, client=?, content=? WHERE id=?");
        $stmt->execute([$title, $category, $imageUrl, $client, $content, $_POST['id']]);
        $msg = "Project updated successfully!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO portfolio (title, category, imageUrl, client, content) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $category, $imageUrl, $client, $content]);
        $msg = "Project added successfully!";
    }
}

if ($action == 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM portfolio WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: manage-portfolio.php?msg=Deleted");
    exit;
}

$editData = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editData = $stmt->fetch();
}

$portfolio = $pdo->query("SELECT * FROM portfolio ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Portfolio | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex">
    <div class="w-64 bg-[#014034] text-white flex flex-col h-screen fixed">
        <div class="p-8 text-2xl font-black border-b border-white/10">Build to Grow</div>
        <nav class="flex-grow p-4 space-y-2 mt-4">
            <a href="dashboard.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-chart-pie"></i><span>Dashboard</span></a>
            <a href="manage-services.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-concierge-bell"></i><span>Services</span></a>
            <a href="manage-portfolio.php" class="flex items-center space-x-3 p-3 bg-white/10 rounded-xl font-bold"><i class="fas fa-briefcase"></i><span>Portfolio</span></a>
            <a href="settings.php" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-xl"><i class="fas fa-cog"></i><span>Settings</span></a>
        </nav>
    </div>

    <div class="flex-grow ml-64 min-h-screen">
        <header class="bg-white shadow-sm p-6 flex justify-between items-center sticky top-0 z-10">
            <h2 class="text-xl font-bold text-gray-800">Portfolio Management</h2>
            <a href="manage-portfolio.php?action=add" class="bg-[#014034] text-white px-6 py-2 rounded-xl font-bold">Add New Project</a>
        </header>

        <main class="p-10">
            <?php if ($action == 'add' || $action == 'edit'): ?>
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 mb-10">
                <h3 class="text-2xl font-bold text-[#014034] mb-8"><?php echo $editData ? 'Edit Project' : 'Add New Project'; ?></h3>
                <form method="POST" class="space-y-6">
                    <?php if ($editData): ?><input type="hidden" name="id" value="<?php echo $editData['id']; ?>"><?php endif; ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Project Title</label>
                            <input type="text" name="title" required value="<?php echo $editData['title'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Category</label>
                            <input type="text" name="category" required value="<?php echo $editData['category'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Image URL (Unsplash or direct link)</label>
                            <input type="url" name="imageUrl" required value="<?php echo $editData['imageUrl'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Client Name</label>
                            <input type="text" name="client" required value="<?php echo $editData['client'] ?? ''; ?>" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Case Study Content</label>
                        <textarea name="content" rows="8" class="w-full px-5 py-3 rounded-xl border outline-none focus:border-[#014034]"><?php echo $editData['content'] ?? ''; ?></textarea>
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" class="bg-[#014034] text-white px-8 py-3 rounded-xl font-bold shadow-lg">Save Project</button>
                        <a href="manage-portfolio.php" class="bg-gray-100 text-gray-600 px-8 py-3 rounded-xl font-bold">Cancel</a>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($portfolio as $p): ?>
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all group">
                    <div class="h-48 overflow-hidden bg-gray-200">
                        <img src="<?php echo $p['imageUrl']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-bold text-[#4DB6AC] uppercase mb-1"><?php echo $p['category']; ?></div>
                        <h4 class="text-xl font-bold text-[#014034] mb-4"><?php echo $p['title']; ?></h4>
                        <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                            <span class="text-sm text-gray-400">Client: <?php echo $p['client']; ?></span>
                            <div class="space-x-4">
                                <a href="manage-portfolio.php?action=edit&id=<?php echo $p['id']; ?>" class="text-blue-600 font-bold text-sm">Edit</a>
                                <a href="manage-portfolio.php?action=delete&id=<?php echo $p['id']; ?>" onclick="return confirm('Delete project?')" class="text-red-600 font-bold text-sm">Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>