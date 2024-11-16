<?php
session_start();  

error_reporting(E_ALL);
ini_set('display_errors', 1);


// Include necessary files
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');


// Create a Reservation object
$reservation = new Reservation($conn);


// Get the reservation ID from the query string
$reservasjonID = $_GET['ReservasjonID'] ?? null;
if (!$reservasjonID) {
    die("Reservation ID is required.");
}
// Fetch reservation details
$reservationDetails = $reservation->getReservationById($reservasjonID);
if (!$reservationDetails) {
    die("Reservation not found.");
}


// Process the deletion if confirmed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $result = $reservation->deleteReservation($reservasjonID);
    echo "<p>$result</p>";
    echo '<a href="AdminReservations.php" class="text-blue-500 hover:underline">Tilbake til reservasjonlisten</a>';
    exit;
}
?>
<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<div class="delete-reservation-section">
    <h2 class="text-xl text-center font-semibold pb-2">Bekreft Sletting av Reservasjon</h2>
    <p class="text-center">Er du sikker på at du vil slette denne reservasjonen?</p>
    <table class="table-auto w-full border-collapse mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ReservasjonID</th>
                <th class="border px-4 py-2">BrukerID</th>
                <th class="border px-4 py-2">RomID</th>
                <th class="border px-4 py-2">Innsjekk</th>
                <th class="border px-4 py-2">Utsjekk</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservationDetails['ReservasjonID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservationDetails['BrukerID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservationDetails['RomID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservationDetails['Innsjekk']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservationDetails['Utsjekk']); ?></td>
            </tr>
        </tbody>
    </table>
    <form method="POST" action="" class="mt-4 text-center">
        <button type="submit" name="confirm" class="bg-red-500 text-white px-4 py-2 mr-2">Ja, slett</button>
        <a href="AdminReservations.php" class="bg-gray-500 text-white px-4 py-2">Nei, gå tilbake</a>
    </form>
</div>
</html>