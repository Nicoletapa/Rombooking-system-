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
                <th class="border px-4 py-2">Handling</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
            <?php
                $today = new DateTime(); // Get current date
                $checkInDate = new DateTime($reservation['Innsjekk']);
                $daysDifference = $today->diff($checkInDate)->days;
                $isCancelable = $checkInDate > $today && $daysDifference > 2; // More than 2 days left 
            ?>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['RomTypeNavn']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Beskrivelse']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Innsjekk']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Utsjekk']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($reservation['Bestillingsdato']); ?></td>
                <td class="border px-4 py-2">
                    <?php if ($isCancelable): ?>
                        <button class="bg-red-500 text-white px-2 py-1 rounded cancel-btn" data-reservation-id="<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>">
                            Avbestill
                        </button> 
                    <?php else: ?>
                        <p class="text-gray-500 text-sm">Kan ikke avbestilles</p>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-center">Ingen reservasjoner funnet.</p>
    <?php endif; ?>

</div>

    <!-- Cancel confirmation modal -->
     <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-md">
            <h3 class="text-xl">Er du sikker på at du vil avbestille reservasjonen?</h3>
            <form id="cancelForm" method="post" action="/Rombooking-system-/Views/Bookings/CancelReservation.php">
                <input type="hidden" name="reservation_id" id="reservation_id">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded mt-4">Ja, avbestill</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mt-4" id="closeModal">Avbryt</button>
            </form>
        </div>
     </div>

    <!-- JavaScript to handle modal -->
    <script>
        // Open modal when cancel button is clicked
        document.querySelectorAll('.cancel-btn').forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-reservation-id');
                document.getElementById('reservation_id').value = reservationId; // Set reservationID in hidden input
                document.getElementById('confirmationModal').classList.remove('hidden'); // Show modal
            });
        });

        // Close modal when 'avbryt' is clicked
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden'); // Hide modal
        });
    </script>
<?php
// Close the database connection
$conn->close();
?>