<?php
    $host = 'localhost';
    $dbname = 'perlygates';
    $user = 'root';  // Replace with your MySQL username
    $pass = '';      // Replace with your MySQL password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected";
        
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
?>
