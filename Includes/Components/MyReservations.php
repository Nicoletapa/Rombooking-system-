<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php'); // Include the Reservation class
$userReservations = new Reservation($conn);
$reservations = $userReservations->getReservationsLoggedInUser();
?>
<div class="container mx-auto p-4">
    <div class="flex justify-end mb-4">
        <button id="cardViewBtn" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Card View</button>
        <button id="tableViewBtn" class="bg-blue-500 text-white px-4 py-2 rounded">Table View</button>
    </div>

    <div id="cardView" class="hidden">
        <?php if (!empty($reservations)): ?>
            <div class="flex flex-col gap-4">
                <?php foreach ($reservations as $reservation): ?>
                    <?php
                        $today = new DateTime(); // Get current date
                        $checkInDate = new DateTime($reservation['Innsjekk']);
                        $daysDifference = $today->diff($checkInDate)->days;
                        $isCancelable = $checkInDate > $today && $daysDifference > 2; // More than 2 days left 
                    ?>
                    <div class="bg-white flex  shadow-md rounded-lg overflow-hidden">
                        <img src="<?php echo htmlspecialchars($reservation['RoomTypeImage']); ?>" alt="Room Image" class="w-80 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($reservation['RomTypeNavn']); ?></h3>
                            <p class="text-gray-600"><?php echo htmlspecialchars($reservation['Beskrivelse']); ?></p>
                            <p class="text-gray-600"><strong>RomNr:</strong> <?php echo htmlspecialchars($reservation['RomID']); ?></p>
                            <p class="text-gray-600"><strong>Innsjekk:</strong> <?php echo htmlspecialchars($reservation['Innsjekk']); ?></p>
                            <p class="text-gray-600"><strong>Utsjekk:</strong> <?php echo htmlspecialchars($reservation['Utsjekk']); ?></p>
                            <p class="text-gray-600"><strong>BestillingsDato:</strong> <?php echo htmlspecialchars($reservation['Bestillingsdato']); ?></p>
                            <div class="mt-4 flex justify-between items-center">
                                <a href="/Rombooking-system-/Includes/utils/ReservationPDF.php?reservation_id=<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>" class="bg-blue-500 text-white px-4 py-2 rounded text-sm flex items-center justify-center">
                                    Last ned PDF
                                </a>
                                <?php if ($isCancelable): ?>
                                    <button class="bg-red-500 text-white px-4 py-2 rounded text-sm flex items-center justify-center cancel-btn" data-reservation-id="<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>">
                                        Avbestill
                                    </button> 
                                <?php else: ?>
                                    <p class="text-gray-500 text-sm">Kan ikke avbestilles</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Ingen reservasjoner funnet.</p>
        <?php endif; ?>
    </div>

    <div id="tableView">
        <?php if (!empty($reservations)): ?>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">RomNr</th>
                        <th class="py-2 px-4 border-b">RomType</th>
                        <th class="py-2 px-4 border-b">Beskrivelse</th>
                        <th class="py-2 px-4 border-b">Innsjekk</th>
                        <th class="py-2 px-4 border-b">Utsjekk</th>
                        <th class="py-2 px-4 border-b">BestillingsDato</th>
                        <th class="py-2 px-4 border-b">Handlinger</th>
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
                            <td class="border px-4 py-2 flex gap-2 items-center">
                                <a href="/Rombooking-system-/Includes/utils/ReservationPDF.php?reservation_id=<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>" class="bg-blue-500 text-white px-2 py-1 rounded text-sm flex items-center justify-center">
                                    Last ned PDF
                                </a>
                                <?php if ($isCancelable): ?>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded text-sm flex items-center justify-center cancel-btn" data-reservation-id="<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>">
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
</div>

<!-- JavaScript to handle view switching -->
<script>
    document.getElementById('cardViewBtn').addEventListener('click', function() {
        document.getElementById('cardView').classList.remove('hidden');
        document.getElementById('tableView').classList.add('hidden');
        document.getElementById('cardViewBtn').classList.add('bg-blue-500');
        document.getElementById('cardViewBtn').classList.remove('bg-gray-500');
        document.getElementById('tableViewBtn').classList.add('bg-gray-500');
        document.getElementById('tableViewBtn').classList.remove('bg-blue-500');
    });

    document.getElementById('tableViewBtn').addEventListener('click', function() {
        document.getElementById('cardView').classList.add('hidden');
        document.getElementById('tableView').classList.remove('hidden');
        document.getElementById('tableViewBtn').classList.add('bg-blue-500');
        document.getElementById('tableViewBtn').classList.remove('bg-gray-500');
        document.getElementById('cardViewBtn').classList.add('bg-gray-500');
        document.getElementById('cardViewBtn').classList.remove('bg-blue-500');
    });

    // Initially show the table view and set the button colors
    document.getElementById('tableView').classList.remove('hidden');
    document.getElementById('cardView').classList.add('hidden');
    document.getElementById('tableViewBtn').classList.add('bg-blue-500');
    document.getElementById('tableViewBtn').classList.remove('bg-gray-500');
    document.getElementById('cardViewBtn').classList.add('bg-gray-500');
    document.getElementById('cardViewBtn').classList.remove('bg-blue-500');
</script>