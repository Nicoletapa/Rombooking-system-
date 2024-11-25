<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');

$user = new User($conn);
$admin = new Admin($conn);

$userID = $_GET['BrukerID'] ?? null;

if (!$userID) {
    die("User ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user = $user->getUserById($userID);
    if (!$user) {
        die("User not found.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'role' => $_POST['role']
    ];

    $success = $admin->editUser($userID, $data);
    if ($success) {
        $message = "User updated successfully!";
    } else {
        $message = "Error updating user.";
    }
    header("Location: /Rombooking-system-/Views/AdminPanel/ManageUsers.php?message=" . urlencode($message));
    exit();
}
