<?php
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/DateFormat.php');

// Fetch the ReservasjonID from the query string
$reservasjonID = isset($_GET['ReservasjonID']) ? $_GET['ReservasjonID'] : '';

// Fetch the reservation details
$sql = "
    SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk,r.Bestillingsdato, r.AntallPersoner, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse, b.Navn, b.Etternavn, b.TlfNr 
    FROM Reservasjon r
    JOIN RomID_RomType rid ON r.RomID = rid.RomID
    JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
    JOIN Bruker b ON r.BrukerID = b.BrukerID
    WHERE r.ReservasjonID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservasjonID);
$stmt->execute();
$result = $stmt->get_result();
$reservation = $result->fetch_assoc();
?> 

<!-- LEGGE TIL RAD FOR EPOST ETTERHVERT -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasjonsdetaljer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-2">
        <h2 class="text-2xl font-bold text-center mb-4">Reservasjonsdetaljer</h2>
        <div class="bg-white/10 shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
            <?php if ($reservation): ?>
            <table class="table-auto w-full border-collapse mb-4">
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
                        <td class="border px-4 py-2">Bestillingsdato</td>
                        <td class="border px-4 py-2"><?php echo formatDate($reservation['Bestillingsdato']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Innsjekk</td>
                        <td class="border px-4 py-2"><?php echo formatDate($reservation['Innsjekk']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Utsjekk</td>
                        <td class="border px-4 py-2"><?php echo formatDate($reservation['Utsjekk']); ?></td>
                    </tr>
                    <tr>
                        <td class="border px-4 py-2">Antall Personer</td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['AntallPersoner']); ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-between">
                <a href="AdminReservations.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tilbake til oversikten</a>
                <a href="DeleteReservation.php?ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Slett reservasjonen</a>
                <a href="AdminEditReservation.php?ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>" class="bg-orange-400 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded">Rediger reservasjon</a>
            </div>
            <?php else: ?>
            <p class="text-center text-red-500">Reservasjonen ble ikke funnet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>