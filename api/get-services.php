<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../db.php';

try {
    $stmt = $pdo->query("SELECT * FROM services ORDER BY id ASC");
    $services = $stmt->fetchAll();
    
    // Decode features JSON for each service
    foreach ($services as &$service) {
        if (isset($service['features'])) {
            $service['features'] = json_decode($service['features'], true);
        }
    }
    
    echo json_encode($services);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>