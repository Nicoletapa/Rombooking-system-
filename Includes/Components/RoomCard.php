<?php
function displayRoomCard($roomID, $roomType, $count, $image, $innsjekk, $utsjekk, $antallPersoner)
{
    session_start();

    $bookingForm = "";
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $bookingForm = "
        <form method='POST' action='../../Views/Bookings/Booking.php'>
            <input type='hidden' name='romID' value='$roomID'> <!-- Room ID is sent -->
            <input type='hidden' name='romNavn' value='$roomType'> <!-- Room name is sent -->
            <input type='hidden' name='innsjekk' value='$innsjekk'>
            <input type='hidden' name='utsjekk' value='$utsjekk'>
            <input type='hidden' name='antallPersoner' value='$antallPersoner'>
            <button type='submit' class='bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded'>
                Book
            </button>
        </form>";
    } else {
        $bookingForm = "<p class='text-red-500'>Du må være logget inn for å booke et rom.</p>";
    }

    return "
    <div class='bg-white shadow-md rounded-lg'>
    <div class='h-72 w-full overflow-hidden'>
        <img src='$image' alt='room image' class='object-cover rounded-t-lg'>
        </div>
        <div class='p-4'>
            <h3 class='text-lg font-semibold mb-2'>$roomType</h3>
            <p class='text-gray-600 mb-4'>Antall tilgjengelig: $count</p>
            $bookingForm
        </div>
    </div>";
}
