<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require '../models/db.php';
require '../vendor/autoload.php'; // Include PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Parse JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? null;
    $action = $input['action'] ?? null;

    if ($id && $action === 'confirm') {
        // Fetch client details from the database using the booking ID
        $stmt = $pdo->prepare("SELECT fullname, email FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $clientName = $result['fullname'];
            $clientEmail = $result['email'];

            // Mark the booking as confirmed
            $updateStmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
            $updateStmt->execute([$id]);

            // Send Confirmation Email using PHPMailer
            $subject = "Booking Confirmation - PearlyGate Residence";
            $message = "Dear $clientName,\n\nYour booking has been successfully confirmed.\n\nThank you for choosing PearlyGate Residence!";
            
            // Create PHPMailer instance
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->isSMTP();                        // Set mailer to use SMTP
                $mail->Host = 'smtp.example.com';        // Set the SMTP server (replace with your SMTP host)
                $mail->SMTPAuth = true;                 // Enable SMTP authentication
                $mail->Username = 'your-email@example.com'; // SMTP username (your email)
                $mail->Password = 'your-email-password';  // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
                $mail->Port = 587;                     // TCP port to connect to

                //Recipients
                $mail->setFrom('noreply@perlygatesresidence.com', 'PearlyGate Residence');
                $mail->addAddress($clientEmail, $clientName); // Add recipient

                // Content
                $mail->isHTML(false);                  // Set email format to plain text
                $mail->Subject = $subject;
                $mail->Body    = $message;

                // Send email
                $mail->send();

                echo json_encode(["success" => true, "message" => "Booking confirmed and email sent."]);
            } catch (Exception $e) {
                echo json_encode(["success" => false, "message" => "Booking confirmed, but email failed to send. Error: " . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(["error" => "Booking not found."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid request."]);
    }
}
?>


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Parse JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = $input['id'] ?? null;

    if ($id) {
        // Delete booking from the database
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Booking deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Booking not found or could not be deleted."]);
        }
    } else {
        echo json_encode(["error" => "Invalid booking ID."]);
    }
}
?>
