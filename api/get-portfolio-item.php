<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM portfolio WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($project ? $project : ["error" => "Project not found"]);
} else {
    echo json_encode(["error" => "No ID provided"]);
}