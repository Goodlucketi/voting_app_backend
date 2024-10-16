<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php';

$stmt = $pdo->query("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$voter = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode (['voter'=> $voter]);

?>