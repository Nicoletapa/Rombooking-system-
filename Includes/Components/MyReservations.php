<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php'); // Include the Reservation class
$userReservations = new Reservation($conn);
$reservations = $userReservations->getReservationsLoggedInUser();
?>

<div class="reservations-section">
    <h2 class="text-xl text-center font-semibold pb-2">Mine Reservasjoner</h2>

    <?php if (!empty($reservations)): ?>
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
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomID']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomTypeNavn']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Beskrivelse']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Innsjekk']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Utsjekk']); ?></td>
                        <td class="border px-4 py-2">Temp</td> <!-- Replace "Temp" with actual booking date when available -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Ingen reservasjoner funnet.</p>
    <?php endif; ?>

</div>

<?php
// Close the database connection
$conn->close();
?>