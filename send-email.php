<?php
session_start();
require 'config/config.php'; // Ensure this includes your PDO connection

// Load Composer's autoloader if you are using Composer
require 'vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and sanitize
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $project = htmlspecialchars($_POST['project']); // Updated to match your table
    $message = htmlspecialchars($_POST['message']);

    // Generate a unique token
    $token = bin2hex(random_bytes(16)); // Generates a random token

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = SMTP_HOST; // Replace with your SMTP host
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME; // Replace with your SMTP username
        $mail->Password = SMTP_PASSWORD; // Replace with your SMTP password
        $mail->SMTPSecure = SMTP_ENCRYPTION; // e.g. 'ssl' or 'tls'
        $mail->Port = SMTP_PORT; // Replace with your SMTP port

        // Set sender and recipient
        $mail->setFrom(SMTP_FROM, $name); // Use the sender's name
        $mail->addAddress('info@onlinegrowthhub.in'); // Change to your actual email address

        // Email subject and body
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission from ' . $name;
        $mail->Body = "<h2>Contact Form Submission</h2>
                       <p><strong>Name:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Project:</strong> $project</p> <!-- Changed from subject to project -->
                       <p><strong>Message:</strong><br/>$message</p>
                       <p><strong>Token:</strong> $token</p>"; // Include token in the email body

        // Send the email
        $mail->send();
        $_SESSION['email_sent'] = true; // Email sent successfully

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, project, message, token) VALUES (:name, :email, :project, :message, :token)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':project' => $project, // Now matches the correct field
            ':message' => $message,
            ':token' => $token // Insert the token into the database
        ]);
    } catch (Exception $e) {
        $_SESSION['email_sent'] = false; // Email failed to send
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }

    // Redirect back to the contact page
    header("Location: contact.php");
    exit; // Stop script execution
}
?>
