<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

$input = json_decode(file_get_contents("php://input"), true);
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_BCRYPT);

    if ($input) {
        $stmt = $pdo->prepare("SELECT email, password FROM users  WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if($user && password_verify($password, $user['password'])){
            session_start();
            $_SESSION['user_id'] = $user['id'];
            json_encode(["success"=>true ,"message"=>"Login Successful"]);
        }else{
            json_encode(["success"=> false, "message"=>"Incorrect Username or Password"]);
        } 
    } else {
        echo json_encode(["success" => false, "message" => "Invalid input"]);
    }
?>
