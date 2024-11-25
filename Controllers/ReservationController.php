<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Reservation.php');

class ReservationController
{
    private $reservationModel;

    public function __construct($conn)
    {
        $this->reservationModel = new Reservation($conn);
    }

    public function showEditReservationForm($reservasjonID)
    {
        $reservation = $this->reservationModel->getReservationById($reservasjonID);

        if (!$reservation) {
            include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/AdminPanel/ReservationNotFound.php');
        } else {
            include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/AdminPanel/AdminEditReservation.php');
        }
    }

    public function handleEditReservation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservasjonID = $_POST['ReservasjonID'];
            $brukerID = $_POST['BrukerID'];
            $romID = $_POST['RomID'];
            $innsjekk = $_POST['Innsjekk'];
            $utsjekk = $_POST['Utsjekk'];

            $message = $this->reservationModel->editReservation($reservasjonID, $brukerID, $romID, $innsjekk, $utsjekk);

            if (strpos($message, 'oppdatert') !== false) {
                header('Location: /Rombooking-system-/Views/AdminPanel/AdminReservations.php');
                exit();
            } else {
                echo $message;
            }
        }
    }

    public function getReservationsLoggedInUser()
    {
        session_start();
        if (!isset($_SESSION['BrukerID'])) {
            header('Location: /Rombooking-system-/Views/Users/Login.php');
            exit();
        }

        $brukerID = $_SESSION['BrukerID'];
        return $this->reservationModel->getReservationsLoggedInUser($brukerID);
    }



    public function showReservationDetails($reservasjonID)
    {
        $reservation = $this->reservationModel->getReservationById($reservasjonID);

        if (!$reservation) {
            include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/AdminPanel/ReservationNotFound.php');
        } else {
            include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/AdminPanel/ReservationDetails.php');
        }
    }

    public function deleteReservation($reservasjonID)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->reservationModel->deleteReservation($reservasjonID);

            if ($result) {
                header('Location: /Rombooking-system-/Views/AdminPanel/AdminReservations.php');
                exit();
            } else {
                echo "Feil ved sletting av reservasjonen.";
            }
        } else {
            $reservation = $this->reservationModel->getReservationById($reservasjonID);

            if ($reservation) {
                include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Views/AdminPanel/DeleteReservation.php');
            } else {
                echo "Reservasjonen ble ikke funnet.";
            }
        }
    }


    public function cancelReservation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reservasjonID = $_POST['reservation_id'];
            if ($this->reservationModel->cancelReservation($reservasjonID)) {
                header('Location: /Rombooking-system-/Views/Users/UserReservations.php');
                exit();
            } else {
                echo "Feil ved avbestilling.";
            }
        }
    }

    public function findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner)
    {
        return $this->reservationModel->findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner);
    }
}
