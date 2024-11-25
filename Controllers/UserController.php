<?php

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');

class UserController
{
    private $userModel;

    public function __construct($conn)
    {
        $this->userModel = new User($conn);
    }

    public function showProfile()
    {

        // Check if user is logged in
        if (!isset($_SESSION['BrukerID'])) {
            header('Location: /Rombooking-system-/Views/Users/Login.php');
            exit();
        }

        $brukerID = $_SESSION['BrukerID'];

        // Fetch total reservations
        $totalReservations = $this->userModel->getTotalReservations($brukerID);

        // Get user details from the session
        $firstname = $_SESSION['firstname'] ?? 'Guest';
        $lastname = $_SESSION['lastname'] ?? '';
        $userName = $_SESSION['UserName'] ?? '';
        $email = $_SESSION['email'] ?? '';
        $phoneNumber = $_SESSION['TlfNr'] ?? '';

        // Generate the avatar URL
        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode("$firstname $lastname") . '&size=128&background=0D8ABC&color=fff';

        // Pass data to the view
        include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/Users/UserPanel.php');
    }
}
