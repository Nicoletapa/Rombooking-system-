<?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/Navbar.php');

error_reporting(E_ALL);
ini_set('display_errors', 1); ?>
<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">


        <?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/SidebarAdminPanel.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <div class="container mx-auto py-8">
                <h2 class="text-2xl font-bold text-center mb-8">Rediger Reservasjon</h2>
                <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
                    <?php if ($reservation): ?>
                        <form method="POST" action="/Rombooking-system-/routes.php">
                            <input type="hidden" name="action" value="edit_reservation">
                            <input type="hidden" name="ReservasjonID"
                                value="<?php echo htmlspecialchars($reservation['ReservasjonID']); ?>">

                            <label for="BrukerID" class="block text-sm font-medium text-gray-700">BrukerID</label>
                            <input type="text" name="BrukerID" id="BrukerID"
                                value="<?php echo htmlspecialchars($reservation['BrukerID']); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <label for="RomID" class="block text-sm font-medium text-gray-700">RomID</label>
                            <input type="text" name="RomID" id="RomID"
                                value="<?php echo htmlspecialchars($reservation['RomID']); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <label for="Innsjekk" class="block text-sm font-medium text-gray-700">Innsjekk</label>
                            <input type="datetime-local" name="Innsjekk" id="Innsjekk"
                                value="<?php echo htmlspecialchars($reservation['Innsjekk']); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <label for="Utsjekk" class="block text-sm font-medium text-gray-700">Utsjekk</label>
                            <input type="datetime-local" name="Utsjekk" id="Utsjekk"
                                value="<?php echo htmlspecialchars($reservation['Utsjekk']); ?>"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                            <button type="submit"
                                class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Oppdater
                                Reservasjon</button>
                        </form>
                    <?php else: ?>
                        <p class="text-center">Reservasjonen ble ikke funnet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>