<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include DB config
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php'); // Include Reservation class

// Usage example
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationID = $_POST['reservation_id'];
    $reservation = new Reservation($conn);
    echo $reservation->cancelReservation($reservationID);
}
