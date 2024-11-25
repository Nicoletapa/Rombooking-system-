<?php

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/Navbar.php');
?>
<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/SidebarAdminPanel.php'); ?>
        <div class="bg-[#B7B2B2] w-full p-4">
            <div class="delete-reservation-section">
                <h2 class="text-xl text-center font-semibold pb-2">Bekreft Sletting av Reservasjon</h2>
                <p class="text-center">Er du sikker på at du vil slette denne reservasjonen?</p>
                <form method="POST" action="/Rombooking-system-/routes.php">
                    <input type="hidden" name="action" value="delete_reservation">
                    <input type="hidden" name="ReservasjonID"
                        value="<?php echo htmlspecialchars($_GET['ReservasjonID'] ?? ''); ?>">

                    <div class="text-center mt-4">
                        <button type="submit" name="confirm" class="bg-red-500 text-white px-4 py-2 rounded">Ja,
                            slett</button>
                        <a href="AdminReservations.php" class="bg-gray-500 text-white px-4 py-2 rounded">Nei, gå
                            tilbake</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>