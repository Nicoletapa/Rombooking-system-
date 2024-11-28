<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Mailer.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $romID = $_POST['romID'];
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];
    $antallPersoner = $_POST['antallPersoner'];
    $brukerID = $_SESSION['BrukerID'];
    $email = $_POST['email']; // Assuming you have the user's email in the form

    // Create a Reservation object
    $reservation = new Reservation($conn);

    // Save the reservation to the database and get the reservation ID
    $reservationId = $reservation->confirmReservation($_POST, $brukerID);

    if ($reservationId) {
        $emailBody = "
            Reservation Confirmation
            Your reservation has been confirmed. Here are the details:
            Reservation ID: {$reservationId}
            Room ID: {$romID}
            Check-in Date: {$innsjekk}
            Check-out Date: {$utsjekk}
            Number of People: {$antallPersoner}
            Thank you for your reservation!
        ";
        $emailBody = strip_tags($emailBody);

        // Send reservation confirmation email
        $mailer = new Mailer();
        $mailer->sendEmail($email, 'Reservation Confirmation', $emailBody);

        echo "Reservation successful! Your reservation ID is: " . $reservationId;
    } else {
        echo "Error: Could not confirm reservation.";
    }
}
?>