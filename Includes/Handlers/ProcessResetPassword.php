<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/PasswordManager.php');

$passwordManager = new PasswordManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Handle password reset and get the result message
    $result = $passwordManager->resetPassword($token, $newPassword, $confirmPassword);

    if ($result === "Passordet ditt har blitt oppdatert. Logg inn med det nye passordet.") {
        // Success: Redirect to login page with a success message
        header("Location: /Rombooking-system-/Views/Users/Login.php?message=" . urlencode($result));
        exit;
    } else {
        // Failure: Stay on the same page and display the error message
        header("Location: /Rombooking-system-/Views/Users/ResetPassword.php?error=" . urlencode($result) . "&token=" . urlencode($token));
        exit;
    }
}