<!DOCTYPE html>
<html>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<head>
    <title>Reservasjonsdetaljer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body>
    <div class="reservations-section">
        <h2 class="text-xl text-center font-semibold pb-2">Reservasjonsdetaljer</h2>
        <?php if (isset($reservation) && !empty($reservation)): ?>
            <table class="table-auto w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">Felt</th>
                        <th class="border px-4 py-2">Verdi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservation as $key => $value): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($key); ?></td>
                            <td class="border px-4 py-2"><?php echo htmlspecialchars($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">Reservasjonen ble ikke funnet eller dataen er tom.</p>
        <?php endif; ?>

        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">
            <a href="/Rombooking-system-/Views/AdminPanel/AdminReservations.php">Tilbake til oversikten</a>
        </button>
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-2">
            <a href="/Rombooking-system-/routes.php?action=delete_reservation&ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>"
                class="bg-red-500 text-white px-4 py-2 rounded">Slett reservasjonen</a>
        </button>
        <button class="bg-orange-400 hover:bg-orange-500 text-white font-bold py-2 px-4 rounded mt-2">
            <a
                href="/Rombooking-system-/routes.php?action=edit_reservation&ReservasjonID=<?php echo $reservation['ReservasjonID']; ?>">Rediger
                reservasjon</a>

    </div>
</body>

</html>