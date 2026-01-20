<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

if (isset($_POST['add'])) {
    $features = json_encode(explode(',', $_POST['features']));
    $stmt = $pdo->prepare("INSERT INTO pricing_plans (name, price, period, features, is_popular) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['price'], $_POST['period'], $features, isset($_POST['is_popular']) ? 1 : 0]);
}

$plans = $pdo->query("SELECT * FROM pricing_plans")->fetchAll();
?>
<body class="p-8 bg-gray-50 font-sans">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Manage Pricing Plans</h1>
        <form method="POST" class="bg-white p-6 rounded-2xl shadow mb-8 grid grid-cols-2 gap-4">
            <input type="text" name="name" placeholder="Plan Name (e.g. Basic)" class="border p-2 rounded" required>
            <input type="text" name="price" placeholder="Price (e.g. $99)" class="border p-2 rounded" required>
            <input type="text" name="period" placeholder="Period (e.g. monthly)" class="border p-2 rounded">
            <input type="text" name="features" placeholder="Features (comma separated)" class="border p-2 rounded col-span-2">
            <label><input type="checkbox" name="is_popular"> Mark as Popular</label>
            <button name="add" class="bg-[#014034] text-white px-4 py-2 rounded col-span-2">Add Plan</button>
        </form>
        <div class="grid gap-4">
            <?php foreach($plans as $p): ?>
                <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                    <div><h3 class="font-bold"><?php echo $p['name']; ?> - <?php echo $p['price']; ?></h3></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>