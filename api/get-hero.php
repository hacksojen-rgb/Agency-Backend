<?php
header('Content-Type: application/json');
require_once '../db.php';
$stmt = $pdo->query("SELECT * FROM hero_slides");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));