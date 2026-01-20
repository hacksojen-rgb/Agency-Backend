<?php
// ১. পিএইচপি এরর ডিসপ্লে চালু করুন যাতে আমরা সমস্যা দেখতে পাই
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

try {
    echo "Starting setup...<br>";

    // ২. site_settings টেবিল আপডেট
    $pdo->exec("CREATE TABLE IF NOT EXISTS site_settings (id SERIAL PRIMARY KEY)");
    
    $cols = [
        "company_name" => "TEXT",
        "address" => "TEXT",
        "phone" => "VARCHAR(50)",
        "email" => "VARCHAR(100)",
        "about_title" => "TEXT",
        "about_text" => "TEXT",
        "logo_url" => "TEXT",
        "facebook_url" => "TEXT",
        "twitter_url" => "TEXT",
        "linkedin_url" => "TEXT"
    ];

    foreach ($cols as $col => $type) {
        $pdo->exec("ALTER TABLE site_settings ADD COLUMN IF NOT EXISTS $col $type");
    }

    // ৩. বাকি টেবিলগুলো তৈরি
    $pdo->exec("CREATE TABLE IF NOT EXISTS hero_slides (id SERIAL PRIMARY KEY, title TEXT, subtitle TEXT, image_url TEXT, button_text VARCHAR(50), button_link VARCHAR(255))");
    $pdo->exec("CREATE TABLE IF NOT EXISTS pricing_plans (id SERIAL PRIMARY KEY, name VARCHAR(100), price VARCHAR(50), period VARCHAR(50), features JSONB, is_popular BOOLEAN DEFAULT FALSE)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (id SERIAL PRIMARY KEY, name VARCHAR(100), role VARCHAR(100), company VARCHAR(100), content TEXT, avatar TEXT)");

    // ৪. ডিফল্ট ইউজার চেক
    $check = $pdo->query("SELECT count(*) FROM site_settings")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("INSERT INTO site_settings (company_name) VALUES ('My Agency')");
    }

    echo "<h2 style='color:green;'>Success! Everything is set up correctly.</h2>";
    echo "<p>Next Step: Go to <a href='/admin/login.php'>Admin Login</a></p>";

} catch (Exception $e) {
    die("<h2 style='color:red;'>Setup Failed:</h2> " . $e->getMessage());
}
?>