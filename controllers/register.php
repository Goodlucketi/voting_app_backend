<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image'];

    if(isset($image)&& $image['error']===0){
        $_target = "../uploads/";
        $filename = basename($image['name']);
        $targetFilePath = $_target . $filename;

        if(move_uploaded_file($image["tmp_name"], $targetFilePath)){
            $image_url = $targetFilePath;
        }else{
            json_encode([
                "success"=>false, 
                "message"=> "File upload Failed"
            ]);
            exit();
        }
    }else{
        json_encode([
            "success"=>false, 
            "message"=> "No File upload"
        ]);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO candidates (name, description, image_url) VALUES(?,?,?)");

    if($stmt->execute([$name, $description, $image_url])){
        echo json_encode([
            'success'=>true,
        ]);
    }else{
        echo json_encode([
            'success'=>false,
            'message'=>"Faile to upload candidate information to the database",
        ]);
    }
}
else{
    echo json_encode([
        'success'=>false,
        'message'=>"Invalid Request Method",
    ]);
}
?>
