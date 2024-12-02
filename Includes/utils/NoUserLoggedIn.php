<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
//Check if user is logged in
if (isset($_SESSION['BrukerID']) ) {
    $brukerID = $_SESSION['BrukerID'];
} else {
    echo "Ingen bruker er logget inn.";
    exit;
}