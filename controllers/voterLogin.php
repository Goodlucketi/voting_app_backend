<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection
require '../vendor/autoload.php';

use \Firebase\JWT\JWT;

$input = json_decode(file_get_contents("php://input"), true);
if($input) {
    $email = $input['email'];
    $password = $input['password'];
   
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users  WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user){
        $check_pwd = password_verify($password, $user['password']);
        if ($check_pwd) {

            $key = 'voting_app2024';
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['name'],
                'exp' => time()+(3600)
            ];

            $jwt = JWT::encode($payload, $key, 'HS256');

            echo json_encode([
                "success"=>true ,
                "message"=>"Login Successful",
                'token'=>$jwt,
            ]);       
        }else{
            echo json_encode([
                'success'=>false, 
                'message'=>"Incorrect email or password"
            ]);
        }  
    }else{
        echo json_encode(["success"=> false, "message"=>"User does not exist"]);
    } 
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>
