<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');
// Initialize the handler
$reservationHandler = new Reservation($conn);
// Fetch the reservation ID from the query string
$reservasjonID = isset($_GET['ReservasjonID']) ? $_GET['ReservasjonID'] : null;
if (!$reservasjonID) {
    die("ReservasjonID er påkrevd.");
}
// Fetch the reservation details
$reservation = $reservationHandler->getReservationById($reservasjonID);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasjonsdetaljer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>
    <div class="reservations-section">
        <h2 class="text-xl text-center font-semibold pb-2">Reservasjonsdetaljer</h2>

        <?php if ($reservation): ?>
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Felt</th>
                        <th class="border px-4 py-2">Verdi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">ReservasjonID</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['ReservasjonID']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">BrukerID</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['BrukerID']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Navn</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Navn']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Etternavn</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Etternavn']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">TelefonNummer</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['TlfNr']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Romnummer</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomID']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">RomType</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomTypeNavn']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Beskrivelse</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Beskrivelse']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Innsjekk</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Innsjekk']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Utsjekk</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Utsjekk']); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="buttons mt-4 flex gap-4">
                <a href="AdminReservations.php"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tilbake til oversikten
                </a>
                <a href="DeleteReservation.php?ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>"
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Slett reservasjonen
                </a>
                <a href="AdminEditReservation.php?ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>"
                    class="bg-orange-400 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded">
                    Rediger reservasjon
                </a>
            </div>
        <?php else: ?>
            <p class="text-center">Reservasjonen ble ikke funnet.</p>
        <?php endif; ?>
    </div>
</body>

</html>