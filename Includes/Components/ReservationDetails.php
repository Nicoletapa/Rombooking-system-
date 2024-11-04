<?php
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../Includes/config.php'; 


// Retrieve the BrukerID and RolleID from the session
if (isset($_SESSION['BrukerID']) && isset($_SESSION['RolleID'])) {
    $brukerID = $_SESSION['BrukerID'];
    $rolleID = $_SESSION['RolleID'];
    
   //RolleID == 2 er admin
    if ($rolleID != 2) {
        echo "Unauthorized access.";
        exit;
    }
} else {
    echo "No user is logged in.";
    exit;
}

// Fetch the ReservasjonID from the query string
$reservasjonID = isset($_GET['ReservasjonID']) ? $_GET['ReservasjonID'] : '';

// Fetch the reservation details
$sql = "
    SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse, b.Navn, b.Etternavn, b.TlfNr 
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
<html>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
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
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['BrukerID']); ?></td>  </tr>  <tr>
                <td class="border px-4 py-2">Navn</td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Navn']); ?></td> </tr>  <tr>
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
    <?php else: ?>
    <p class="text-center">Reservasjonen ble ikke funnet.</p>
    <?php endif; ?>
</div>
</html>