<?php 

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
//Check if user is logged in
if (isset($_SESSION['RolleID']) ) {
    $rolleID = $_SESSION['RolleID'];
    if ($rolleID != 2) {
        echo "Unauthorized access.";
        exit;
    }
}
