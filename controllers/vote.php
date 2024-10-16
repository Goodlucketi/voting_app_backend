<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$headers = apache_request_headers();
$authHeader = $headers['Authorization'] ?? '';

if(!$authHeader){
    echo json_encode([
        "success"=>false,
        "message"=> "No token Provided"
    ]);
    exit();
}

$token = str_replace("Bearer ", "", $authHeader);
$key = 'voting_app2024';

try {
    $decoded = JWT::decode($token, new Key($key, 'HS256'));
    $user_id = $decoded->user_id;

    $input = json_decode(file_get_contents("php://input"), true);
    if($input){
        $candidate_id = $input['candidateId'];
    }
    else{
        echo json_encode([
            "success"=>false,
            "message"=> "No Candidate Id"
        ]);
        exit();
    }
    if(!$candidate_id){
        echo json_encode([
            "success"=>false,
            "message"=> "No Candidate selected"
        ]);
        exit();
    }

    $stmt = $pdo->prepare("SELECT id FROM votes WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $voter = $stmt->fetch();

    if($voter){
        echo json_encode([
            "success"=>false,
            "message"=> "You have already voted"
        ]);
    }else{
        $stmt = $pdo->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?,?)");
        if($stmt->execute([$user_id, $candidate_id])){
            echo json_encode([
                "success"=>true,
                "message"=> "Vote Successful"
            ]);
        }else{
            echo json_encode([
                "success"=>false,
                "message"=> "Failed to record vote"
            ]);
        };

    }
} catch (Exception $e) {
    echo json_encode([
        "success"=>false,
        "message"=> $e->getMessage()
    ]);
}
?>
