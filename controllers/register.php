<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

require '../models/db.php'; // Database connection

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $image = $_FILES['image'];
   
    // Check if candidate already exists
    $check_candidate = $pdo->prepare("SELECT email FROM candidates WHERE email = ?");
    $check_candidate->execute([$email]);
    $user = $check_candidate->fetch();
    
    if($user){
        echo json_encode([
            'success'=> false,
            'message'=> 'Candidate alrealy exists',
        ]);
        exit();
    }

    // check for picture upload
    if(isset($image)&& $image['error']===0){
        $_target = "../uploads/";
        $filename = basename($image['name']);
        $targetFilePath = $_target . $filename;

        // Validate accepted files
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($image['tmp_name']);
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'
            ]);
            exit();
        }

        // Move file to server
        if(move_uploaded_file($image["tmp_name"], $targetFilePath)){
            $image_url = $targetFilePath;
        }else{
           echo json_encode([
                "success"=>false, 
                "message"=> "File upload Failed"
            ]);
            exit();
        }
    }else{
        echo json_encode([
            "success"=>false, 
            "message"=> "No File upload"
        ]);
        exit();
    }

    // Insert Candidate data to database

    $stmt = $pdo->prepare("INSERT INTO candidates (name, email, phone, description, image_url) VALUES(?,?,?,?,?)");

    if($stmt->execute([$name,$email,$phone, $description, $image_url])){
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
