<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the Reservation class


class Reservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }



    public function confirmReservation($postData, $userId)
    {
        // Extract data from the form
        $romID = $postData['romID'];
        $innsjekk = $postData['innsjekk'];
        $utsjekk = $postData['utsjekk'];
        $antallPersoner = $postData['antallPersoner'];
        $bestillingsdato = date('Y-m-d'); // Current date

        // SQL query
        $sql = "INSERT INTO Reservasjon (RomID, BrukerID, Innsjekk, Utsjekk, AntallPersoner, Bestillingsdato) 
                VALUES (?, ?, ?, ?, ?, ?)";

        // Use prepared statement to avoid SQL injection
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Prepared statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("iissis", $romID, $userId, $innsjekk, $utsjekk, $antallPersoner, $bestillingsdato);

        // Execute the query and handle the response
        if ($stmt->execute()) {
            $this->displaySuccessMessage();
        } else {
            $this->displayErrorMessage();
        }

        // Close the statement
        $stmt->close();
    }

    private function displaySuccessMessage()
    {
        echo "<p class='text-green-500 my-4'>Reservasjonen din er bekreftet!</p>";
        echo "
        <script>
            alert('Reservasjonen din er bekreftet! Du blir nå omdirigert til startsiden.');
            window.location.href = '../../index.php';
        </script>";
    }

    private function displayErrorMessage()
    {
        echo "<p class='text-red-500 my-4'>Det oppsto en feil under bestillingen. Prøv igjen senere.</p>";
    }


    public function getReservationById($reservasjonID)
    {
        $sql = "
            SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse, 
                   b.Navn, b.Etternavn, b.TlfNr
            FROM Reservasjon r
            JOIN RomID_RomType rid ON r.RomID = rid.RomID
            JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
            JOIN Bruker b ON r.BrukerID = b.BrukerID
            WHERE r.ReservasjonID = ?
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $reservasjonID);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
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

    public function cancelReservation($reservationID)
    {
        if (empty($reservationID)) {
            return "<p class='text-red-500'>Ugyldig reservasjon.</p>";
        }

        // Fetch reservation check-in date
        $query = "SELECT Innsjekk FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            die("Failed to prepare statement: " . $this->conn->error);
        }

        $stmt->bind_param('i', $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reservation = $result->fetch_assoc();
            $checkInDate = new DateTime($reservation['Innsjekk']);
            $today = new DateTime();
            $daysDifference = $today->diff($checkInDate)->days;

            if ($checkInDate > $today && $daysDifference > 2) {
                // Proceed with deletion
                return $this->deleteReservationAndRedirect($reservationID);
            } else {
                return "<p class='text-red-500'>Reservasjonen kan ikke avbestilles mindre enn 2 dager før innsjekk.</p>";
            }
        } else {
            return "<p class='text-red-500'>Reservasjonen ble ikke funnet.</p>";
        }
    }

    private function deleteReservationAndRedirect($reservationID)
    {
        // Prepare the SQL query to delete the reservation
        $sql = "DELETE FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Failed to prepare delete statement: " . $this->conn->error);
        }

        $stmt->bind_param("i", $reservationID);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            // Set success message and redirect
            header("Location: http://localhost/RomBooking-System-/Views/Users/UserReservations.php?message=success");
            exit;
        } else {
            return "<p class='text-red-500'>Kunne ikke avbestille reservasjonen. Vennligst ta kontakt med vår kundeservice.</p>";
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


  
    public function availableRoomPostRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $innsjekk = $_POST['innsjekk'];
            $utsjekk = $_POST['utsjekk'];
            $romtype = $_POST['romtype'];
            $antallPersoner = $_POST['antallVoksne'] + $_POST['antallBarn'];

            return $this->findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner);
        }

        return null;
    }

    private function findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner)
    {
        $sql = "SELECT RomID_RomType.RomID, Romtype.RomTypeNavn, Romtype.RoomTypeImage 
                FROM RomID_RomType 
                JOIN Romtype ON RomID_RomType.RomTypeID = Romtype.RomtypeID
                WHERE RomID_RomType.RomID NOT IN (
                    SELECT RomID 
                    FROM Reservasjon 
                    WHERE ('$innsjekk' BETWEEN Innsjekk AND Utsjekk) 
                    OR ('$utsjekk' BETWEEN Innsjekk AND Utsjekk)
                    OR (Innsjekk BETWEEN '$innsjekk' AND '$utsjekk')
                )
                AND Romtype.RomKapsitet >= $antallPersoner";

        $result = $this->conn->query($sql);
        return $this->processRoomResults($result, $innsjekk, $utsjekk, $antallPersoner);
    }

    private function processRoomResults($result, $innsjekk, $utsjekk, $antallPersoner)
    {
        $roomTypeCounts = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $roomID = $row["RomID"];
                $roomType = $row["RomTypeNavn"];
                $RoomImage = $row["RoomTypeImage"];

                if (isset($roomTypeCounts[$roomType])) {
                    $roomTypeCounts[$roomType]['count']++;
                } else {
                    $roomTypeCounts[$roomType] = [
                        'count' => 1,
                        'RoomTypeImage' => $RoomImage,
                        'RomID' => $roomID
                    ];
                }
            }

            return $this->generateOutput($roomTypeCounts, $innsjekk, $utsjekk, $antallPersoner);
        }

        return "<p class='text-red-500 my-4'>Ingen rom er tilgjengelige for dette antall personer i dette tidsrommet.</p>";
    }

    private function generateOutput($roomTypeCounts, $innsjekk, $utsjekk, $antallPersoner)
    {
        include '../../Includes/Components/RoomCard.php'; // Include RoomCard component

        $output = "<h2 class='text-xl font-semibold my-4 text-center'>Ledige rom fra $innsjekk til $utsjekk for $antallPersoner personer:</h2>";
        $output .= "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>";

        foreach ($roomTypeCounts as $roomType => $data) {
            $count = $data['count'];
            $image = $data['RoomTypeImage'];
            $roomID = $data['RomID'];
            $output .= displayRoomCard($roomID, $roomType, $count, $image, $innsjekk, $utsjekk, $antallPersoner);
        }

        $output .= "</div>";
        return $output;
    }

    public function __destruct()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

