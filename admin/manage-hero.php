<?php
session_start();
require_once '../db.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

if (isset($_POST['add'])) {
    $stmt = $pdo->prepare("INSERT INTO hero_slides (title, subtitle, image_url, button_text, button_link) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['subtitle'], $_POST['image_url'], $_POST['button_text'], $_POST['button_link']]);
}

$slides = $pdo->query("SELECT * FROM hero_slides")->fetchAll();
?>
<body class="p-8 bg-gray-50">
    <h1 class="text-2xl font-bold mb-6">Manage Hero Slider</h1>
    <form method="POST" class="bg-white p-6 rounded shadow mb-8">
        <input type="text" name="title" placeholder="Main Title" class="border p-2 mr-2" required>
        <input type="text" name="image_url" placeholder="Image URL" class="border p-2 mr-2" required>
        <button name="add" class="bg-green-600 text-white px-4 py-2 rounded">Add Slide</button>
    </form>
    <div class="grid gap-4">
        <?php foreach($slides as $s): ?>
            <div class="bg-white p-4 rounded shadow flex justify-between">
                <span><?php echo $s['title']; ?></span>
                <img src="<?php echo $s['image_url']; ?>" class="h-10">
            </div>
        <?php endforeach; ?>
    </div>
</body>