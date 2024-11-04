<?php 
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
//Check if user is logged in
if (isset($_SESSION['BrukerID']) ) {
    $brukerID = $_SESSION['BrukerID'];
} else {
    echo "No user is logged in.";
    exit;
}