<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');

?>
<nav class="w-1/4 flex flex-col gap-4 font-medium p-4">


    <!-- Mine Bestillinger -->
    <a href="/RomBooking-System-/Views/AdminPanel/AdminReservations.php"
        class="flex gap-2 hover:bg-gray-200 rounded-md">
        <i class="fa-solid fa-file-invoice h-full flex justify-center items-center w-5"></i><span class="text-xl">
            Reservasjoner</span>
    </a>



    <!-- BYTT PATHEN-->
    <a href="/RomBooking-System-/Views/AdminPanel/ManageUsers.php" class="flex gap-2 hover:bg-gray-200 rounded-md">
        <i class="fa-solid fa-key h-full flex justify-center items-center w-5"></i><span class="text-xl">Brukere</span>
    </a>
</nav>