<?php

include('../../Includes/Classes/Reservation.php');
$reservation = new Reservation($conn);
$output = $reservation->handlePostRequest();
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
                if (isset($output)) {
                    echo $output;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>