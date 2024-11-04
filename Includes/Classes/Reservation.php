<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the Reservation class


class Reservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
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
            SELECT r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse
            FROM Reservasjon r
            JOIN RomID_RomType rid ON r.RomID = rid.RomID
            JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
            WHERE r.BrukerID = ?
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
}
