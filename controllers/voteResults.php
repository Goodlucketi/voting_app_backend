<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

$stmt = $pdo->query("SELECT 
    candidates.name AS candidate_name, 
    COUNT(votes.id) AS vote_count, 
    users.name AS voter_name, 
    candidates.id,
    users.id
    FROM votes
    JOIN candidates ON votes.candidate_id = candidates.id
    JOIN users ON votes.user_id = users.id
    GROUP BY candidates.id, candidates.name 
    ORDER BY vote_count DESC");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["success" => true, "results" => $results]);
?>
