<?php
header('Content-Type: application/json');
require_once '../db.php';
$stmt = $pdo->query("SELECT * FROM pricing_plans");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));