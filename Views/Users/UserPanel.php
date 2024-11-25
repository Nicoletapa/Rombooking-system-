<?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/Navbar.php'); ?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/SidebarProfileNav.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <div id="profile" class="section" style="display: block;">
                <h2 class="text-center text-xl font-semibold">Min Profil</h2>
                <p class="text-center text-gray-700">Velkommen til din profil.</p>

                <div class="flex flex-col items-center gap-6 h-[95%] p-4">
                    <img src="<?php echo htmlspecialchars($avatarUrl); ?>" alt="Avatar" class="rounded-full">
                    <div class="flex justify-between w-full items-center px-1">
                        <span class="text-lg"><span class="font-semibold">Brukernavn:
                            </span><?php echo htmlspecialchars($userName); ?></span>
                        <span class="text-lg"><span class="font-semibold">Email:
                            </span><?php echo htmlspecialchars($email); ?></span>
                        <span class="text-lg"><span class="font-semibold">Telefon:
                            </span><?php echo htmlspecialchars($phoneNumber); ?></span>
                    </div>
                    <div class="w-full">
                        <div class="w-full h-48 flex gap-4">
                            <div
                                class="border-l-4 border-green-700 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span
                                    class="text-3xl font-medium"><?php echo htmlspecialchars($totalReservations); ?></span>
                                <span>Antall Reservasjoner</span>
                            </div>
                            <div
                                class="border-l-4 border-cyan-500 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span class="text-3xl font-medium">123</span>
                                <span>Placeholder</span>
                            </div>
                            <div
                                class="border-l-4 border-blue-500 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span class="text-3xl font-medium">123</span>
                                <span>Placeholder</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>