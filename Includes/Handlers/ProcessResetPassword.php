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

    // Redirect back to the login page with a result message
    header("Location: /Rombooking-system-/Views/Users/Login.php?message=" . urlencode($result));
    exit;
}
