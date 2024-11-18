<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/PasswordManager.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Mailer.php');

// Database and mailer setup
$passwordManager = new PasswordManager($conn);
$mailer = new Mailer();

// Get POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Call the class method to handle sending the reset link
    $result = $passwordManager->sendResetLink($email, $mailer);

    // Redirect to ForgotPassword with a message
    header("Location: /Rombooking-system-/Views/Users/ForgotPassword.php?message=" . urlencode($result));
    exit;
}
