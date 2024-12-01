<?php

// Enable error reporting for debugging during development
error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Display errors on the screen

// Include the configuration file for database or app-specific constants
require_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');

// Define the Mailer class to handle email-related functionalities
class Mailer
{
    // Function to send an email
    public function sendEmail($to, $subject, $body)
    {
        // Include necessary PHPMailer classes for email functionality
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/PHPMailer.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/SMTP.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/PHPMailer/src/Exception.php');

        // Create a new instance of PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer();

        // Set PHPMailer to use SMTP (Simple Mail Transfer Protocol)
        $mail->isSMTP();

        // Configure the SMTP server details
        $mail->Host = 'smtp.gmail.com'; // The SMTP server for sending emails
        $mail->SMTPAuth = true; // Enable SMTP authentication

        // Include private configuration for sensitive credentials
        $config = include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Private/config.private.php');

        // Set the SMTP username and password from the private configuration file
        $mail->Username = $config['mail_username']; // Your email address
        $mail->Password = $config['mail_password']; // Your email password

        // Specify the security protocol and port for SMTP
        $mail->SMTPSecure = 'tls'; // Use TLS (Transport Layer Security)
        $mail->Port = 587; // Standard port for TLS-encrypted SMTP

        // Set the sender's email and name
        $mail->setFrom('thevithach@gmail.com', $subject); // Sender email and name

        // Add the recipient's email address
        $mail->addAddress($to); // Recipient's email address

        // Set the email subject and body content
        $mail->Subject = $subject; // Email subject
        $mail->Body = $body; // Email body content

        // Attempt to send the email and handle potential errors
        if (!$mail->send()) {
            // If sending fails, throw an exception with a custom error message
            throw new Exception("Kunne ikke sende e-posten."); // Error: "Could not send the email."
        }
    }
}