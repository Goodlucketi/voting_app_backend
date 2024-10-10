<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

$input = json_decode(file_get_contents("php://input"), true);
$fullname = $input['fullname'];
$email = $input['email'];
$password = password_hash($input['password'], PASSWORD_BCRYPT);

if ($input) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    if($stmt->execute([$fullname, $email, $password])){
        json_encode(["success" => true, "message"=> "Registration Successful"]);
    }else{
        json_encode(["success" => false, "message"=> "Registration Failed"]);
    };
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
