<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the Reservation class


class Reservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function getReservationById($reservasjonID)
    {
        $sql = "
            SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse
            FROM Reservasjon r
            JOIN RomID_RomType rid ON r.RomID = rid.RomID
            JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
            WHERE r.ReservasjonID = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $reservasjonID);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method to fetch reservations for the logged-in user
    public function getReservationsLoggedInUser()
    {
        session_start(); // Ensure session is started

        // Check if the user is logged in
        if (!isset($_SESSION['BrukerID'])) {
            return "No user is logged in.";
        }

        $brukerID = $_SESSION['BrukerID']; // Get the user ID from the session

        // Prepare the SQL query to fetch reservations
        $sql = "
            SELECT r.ReservasjonID, r.RomID, r.Innsjekk, r.Utsjekk, r.Bestillingsdato ,rt.RomTypeNavn, rt.Beskrivelse
            FROM Reservasjon r
            JOIN RomID_RomType rid ON r.RomID = rid.RomID
            JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
            WHERE r.BrukerID = ?
            ORDER BY r.Bestillingsdato DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $brukerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }

        $stmt->close();
        return $reservations;
    }

    public function editReservation($reservasjonID, $brukerID, $romID, $innsjekk, $utsjekk)
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php';
        // Prepare the SQL query to update the reservation
        $sql = "
            UPDATE Reservasjon
            SET BrukerID = ?, RomID = ?, Innsjekk = ?, Utsjekk = ?
            WHERE ReservasjonID = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissi", $brukerID, $romID, $innsjekk, $utsjekk, $reservasjonID);
        if ($stmt->execute()) {
            return "Reservasjonen ble oppdatert!";
        } else {
            return "Feil ved oppdatering av reservasjonen: " . $this->conn->error;
        }
    }

    public function deleteReservation($reservasjonID)
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php';
        // Prepare the SQL query to delete the reservation
        $sql = "DELETE FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $reservasjonID);
        if ($stmt->execute()) {
            return "Reservasjonen ble slettet!";
        } else {
            return "Feil ved sletting av reservasjonen: " . $this->conn->error;
        }
    }

}