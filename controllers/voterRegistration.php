<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

$input = json_decode(file_get_contents("php://input"), true);

if ($input) {
    $fullname = $input['fullname'];
    $email = $input['email'];
    $phone = $input['phone'];
    $password = password_hash($input['password'], PASSWORD_BCRYPT);

    $check_candidate = $pdo->prepare("SELECT email FROM users WHERE email = ?");
    $check_candidate->execute([$email]);
    $user = $check_candidate->fetch();
    
    if($user){
        echo json_encode([
            'success'=> false,
            'message'=> 'Candidate alrealy exists',
        ]);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?,?,?,?)");
    
    if($stmt->execute([$fullname, $email, $phone, $password])){
        echo json_encode(["success" => true, 
        "message"=> "Registration Successful",
    ]);
    }else{
        echo json_encode([
            "success" => false, 
            "message"=> "Registration Failed",
        ]);
    };
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid input",
    ]);
}
?>
