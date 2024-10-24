<?php
function displayRoomCard($roomType, $count, $innsjekk, $utsjekk, $antallPersoner)
{
    return "
    <div class='bg-white shadow-md rounded-lg'>
        <img src='/RomBooking-System-/Public/Images/Enkeltrom.avif' alt='room image' class='object-cover rounded-t-lg'>
<div class='p-4'>
        <h3 class='text-lg font-semibold mb-2'>$roomType</h3>
        
        <p class='text-gray-600 mb-4'>Antall tilgjengelig: $count</p>
        <form method='POST' action='booking_handler.php'>
            <input type='hidden' name='romtype' value='$roomType'>
            <input type='hidden' name='innsjekk' value='$innsjekk'>
            <input type='hidden' name='utsjekk' value='$utsjekk'>
            <input type='hidden' name='antallPersoner' value='$antallPersoner'>
            <button type='submit' class='bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded'>
                Book
            </button>
        </form>
        </div>
    </div>";
}
