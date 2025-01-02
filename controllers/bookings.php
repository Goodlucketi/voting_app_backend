<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php';

$stmt = $pdo->query("SELECT * FROM bookings");

$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode (['clients'=> $clients]);

?>