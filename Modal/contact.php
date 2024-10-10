<?php
// Load Composer's autoloader
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'path_to/PHPMailer/src/Exception.php';
require 'path_to/PHPMailer/src/PHPMailer.php';
require 'path_to/PHPMailer/src/SMTP.php';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Initialize an empty response array
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $mobile = sanitize_input($_POST['mobile']);
    $message = sanitize_input($_POST['message']);
    
    if (empty($name) || empty($email) || empty($mobile) || empty($message)) {
        $response['status'] = 'error';
        $response['message'] = 'Please fill all required fields.';
    } else {
        // Create instance of PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';             // Set the SMTP server
            $mail->SMTPAuth   = true;                         // Enable SMTP authentication
            $mail->Username   = 'your-email@gmail.com';       // SMTP username
            $mail->Password   = 'rrqd vzhw hald cixo';              // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587;                          // TCP port to connect to

            // Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('your-email@example.com');       // Add the recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "Name: $name<br>Email: $email<br>Phone: $mobile<br>Message:<br>$message";

            // Send the email
            $mail->send();
            $response['status'] = 'success';
            $response['message'] = 'Your message has been received, We will contact you soon.';
        } catch (Exception $e) {
            $response['status'] = 'error';
            $response['message'] = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);