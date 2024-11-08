<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');


function validateCheckIn($innsjekk, $utsjekk) {
    $innsjekkDate = new DateTime($innsjekk);
    $utsjekkDate = new DateTime($utsjekk);
    $currentDate = new DateTime();

    if ($innsjekkDate < $currentDate) {
        return false;
    }

    if ($utsjekkDate <= $innsjekkDate) {
        return false;
    }

    return true;
}

?>