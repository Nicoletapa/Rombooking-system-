<?php


include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');
$reservation = new Reservation($conn);
$output = $reservation->availableRoomPostRequest();
session_start();

$errors = isset($_GET['errors']) ? $_GET['errors'] : [];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservasjon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include('../../Includes/Layout/Navbar.php'); ?>
    <div class="h-[85vh]">
        <div class=" h-1/2 bg-[#B7B2B2]">
            <div class="container mx-auto flex gap-8 flex justify-center items-center h-full px-4">
                <?php include("../../Includes/Components/RoomSearchBar.php"); ?>
            </div>
            <div class="container mx-auto pb-2">
                <?php

if (!empty($errors)) {
    echo '<div class="error-messages">';
    foreach ($errors as $error) {
        echo '<p class="text-red-500 px-4">' . htmlspecialchars($error) . '</p>';
    }
    echo '</div>';
} else {
    if (isset($output)) {
        echo $output;
    }

}
                
                ?>
            </div>
        </div>
    </div>
</body>

</html>