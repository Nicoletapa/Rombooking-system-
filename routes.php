<?php
include('Controllers/ReservationController.php');
include('Includes/config.php');

$controller = new ReservationController($conn);

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'cancel_reservation':
            $controller->cancelReservation();
            break;

        case 'delete_reservation':
            $reservasjonID = $_POST['ReservasjonID'] ?? null;
            if ($reservasjonID) {
                $controller->deleteReservation($reservasjonID);
            } else {
                echo "ReservasjonID mangler.";
            }
            break;
        case 'edit_reservation':
            $controller->handleEditReservation();
            break;

        default:
            echo "Unknown action.";
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'details':
            $reservasjonID = $_GET['ReservasjonID'] ?? null;
            if ($reservasjonID) {
                $controller->showReservationDetails($reservasjonID);
            } else {
                echo "ReservasjonID mangler.";
            }
            break;

        case 'edit_reservation':
            $reservasjonID = $_GET['ReservasjonID'] ?? null;
            if ($reservasjonID) {
                $controller->showEditReservationForm($reservasjonID);
            } else {
                echo "ReservasjonID mangler.";
            }
            break;

        case 'edit_reservation':
            $controller->handleEditReservation();
            break;

        case 'delete_reservation':
            $reservasjonID = $_GET['ReservasjonID'] ?? null;
            if ($reservasjonID) {
                $controller->deleteReservation($reservasjonID);
            } else {
                echo "ReservasjonID mangler.";
            }
            break;

        default:
            include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/index.php');
    }
}
