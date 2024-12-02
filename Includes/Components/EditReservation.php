<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); 
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');

// Create a Reservation instance
$reservationInstance = new Reservation($conn);

// Fetch the reservation details
$reservasjonID = isset($_GET['ReservasjonID']) ? $_GET['ReservasjonID'] : '';
$reservation = $reservationInstance->getReservationById($reservasjonID);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brukerID = $_POST['BrukerID'];
    $romID = $_POST['RomID'];
    $innsjekk = $_POST['Innsjekk'];
    $utsjekk = $_POST['Utsjekk'];

    $message = $reservationInstance->editReservation($reservasjonID, $brukerID, $romID, $innsjekk, $utsjekk);
    if (strpos($message, 'oppdatert') !== false) {
        header('Location: ../../Views/AdminPanel/AdminReservations.php');
        exit();
    } else {
        echo $message;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger Reservasjon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-bold text-center mb-8">Rediger Reservasjon</h2>
        <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
            <?php if ($reservation): ?>
            <form method="POST" action="">
                <label for="BrukerID" class="block text-sm font-medium text-gray-700">BrukerID</label>
                <input type="text" name="BrukerID" id="BrukerID" value="<?php echo htmlspecialchars($reservation['BrukerID']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                <label for="RomID" class="block text-sm font-medium text-gray-700">RomID</label>
                <input type="text" name="RomID" id="RomID" value="<?php echo htmlspecialchars($reservation['RomID']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                <label for="Innsjekk" class="block text-sm font-medium text-gray-700">Innsjekk</label>
                <input type="datetime-local" name="Innsjekk" id="Innsjekk" value="<?php echo htmlspecialchars($reservation['Innsjekk']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                <label for="Utsjekk" class="block text-sm font-medium text-gray-700">Utsjekk</label>
                <input type="datetime-local" name="Utsjekk" id="Utsjekk" value="<?php echo htmlspecialchars($reservation['Utsjekk']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                <button type="submit" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Oppdater Reservasjon</button>
            </form>
            <?php else: ?>
            <p class="text-center">Reservasjonen ble ikke funnet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>