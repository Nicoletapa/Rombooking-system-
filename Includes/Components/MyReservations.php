<?php
session_start();  // Start session

// Assuming a connection to the database is already established
include '../../Includes/config.php';

// Retrieve the BrukerID from the session
if (isset($_SESSION['BrukerID'])) {
    $brukerID = $_SESSION['BrukerID'];
} else {
    echo "No user is logged in.";
    exit;
}

// Fetch the reservations with room type details for the logged-in user
$sql = "
    SELECT r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse
    FROM Reservasjon r
    JOIN RomID_RomType rid ON r.RomID = rid.RomID
    JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
    WHERE r.BrukerID = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $brukerID);
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="reservations-section">
    <h2 class="text-xl text-center font-semibold pb-2">Mine Reservasjoner</h2>

    <?php if ($result->num_rows > 0): ?>
    <table class="table-auto w-full border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">Romnummer</th>
                <th class="border px-4 py-2">RomType</th>
                <th class="border px-4 py-2">Beskrivelse</th>
                <th class="border px-4 py-2">Innsjekk</th>
                <th class="border px-4 py-2">Utsjekk</th>
                <th class="border px-4 py-2">Bestillings Dato</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['RomID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['RomTypeNavn']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['Beskrivelse']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['Innsjekk']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['Utsjekk']); ?></td>
                <td class="border px-4 py-2">Temp</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-center">Ingen reservasjoner funnet.</p>
    <?php endif; ?>

</div>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>