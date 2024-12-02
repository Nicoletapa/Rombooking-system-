<?php

// Assuming a connection to the database is already established
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the database configuration file
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php'); // Include the User class
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php'); // Include the Reservation class

$reservation = new Reservation($conn);

// Get the total number of reservations for the current user
$total_reservations = $reservation->countTotalReservations($_SESSION['BrukerID']);

// Generate the URL for the user's avatar
$avatarUrl = $reservation->generateAvatarUrl();

?>



<div id="profile" class="section" style="display: block;">
    <h2 class="text-center text-xl font-semibold">Min Profil</h2>
    <!-- Add profile-related content here -->
    <p class="text-center text-gray-700">Velkommen til din profil.</p>

    <div class="flex flex-col items-center gap-6 h-[95%] p-4">
        <img src="<?php echo $avatarUrl; ?>" alt="Avatar" class="rounded-full">
        <div class="flex justify-between w-full items-center px-1">
            <span class="text-lg"><span class="font-semibold">Brukernavn:
                </span><?php echo $_SESSION['UserName']; ?></span>
            <span class="text-lg"><span class="font-semibold">Email:
                </span><?php echo $_SESSION['email']; ?></span>

            <span class="text-lg"><span class="font-semibold">Telefon:
                </span><?php echo $_SESSION['TlfNr']; ?></span>
        </div>
        <div class="w-full">
            <div class="w-full h-48 flex gap-4 ">
                <div
                    class="border-l-4 border-green-700 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                    <span class="text-3xl font-medium"><?php echo $total_reservations; ?></span>
                    <span>Antall Reservasjoner</span>
                </div>
                <div
                    class="border-l-4 border-cyan-500  bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
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