<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

$input = json_decode(file_get_contents("php://input"), true);

if ($input) {
    $fullname = $input['fullName'];
    $email = $input['email'];
    $phone = $input['phone'];
    $checkIn = $input['checkIn'];
    $checkOut = $input['checkOut'];
    $guest = $input['guests'];
    $roomType = $input['roomType'];
    $numRooms = $input['numRooms'];
    $days = $input['days'];
    $totalCost = $input['totalCost'];

    $stmt = $pdo->prepare("INSERT INTO bookings (fullname, email, phone, check_in, check_out, room_type, guests, num_of_rooms, days, total_cost) VALUES (?,?,?,?,?,?,?,?,?,?)");
    
    if($stmt->execute([$fullname, $email, $phone,$checkIn, $checkOut, $roomType, $guest, $numRooms, $days, $totalCost])){
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