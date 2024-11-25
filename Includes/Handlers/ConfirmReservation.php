<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Usage
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $brukerID = $_SESSION['BrukerID']; // Ensure this matches your session variable name

    $handler = new Reservation($conn);
    $handler->confirmReservation($_POST, $brukerID);
}
