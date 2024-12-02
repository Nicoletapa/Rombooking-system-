<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the Reservation class


class Reservation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Count the total number of reservations for a specific user.
     *
     * @param int $brukerID User ID.
     * @return int Total number of reservations.
     */
    public function countTotalReservations($brukerID)
    {
        $sql = "SELECT COUNT(*) AS total_reservations FROM Reservasjon WHERE BrukerID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Forberedt uttalelse mislyktes: " . $this->conn->error);
        }

        $stmt->bind_param("i", $brukerID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total_reservations'] ?? 0; // Return 0 if no reservations found
    }

    /**
     * Generate an avatar URL based on the user's session data.
     *
     * @return string Avatar URL.
     */
    public function generateAvatarUrl()
    {
        if (isset($_SESSION['firstname']) && isset($_SESSION['lastname'])) {
            $firstname = $_SESSION['firstname'];
            $lastname = $_SESSION['lastname'];

            // Generate avatar URL with the user's initials
            return 'https://ui-avatars.com/api/?name=' . urlencode($firstname . ' ' . $lastname) . '&size=128&background=0D8ABC&color=fff';
        } else {
            // Default avatar for guests
            return 'https://ui-avatars.com/api/?name=Guest&size=128&background=CCCCCC&color=000000';
        }
    }



    /**
     * Confirm a reservation and save it in the database.
     *
     * @param array $postData Reservation data from the form.
     * @param int $userId User ID.
     * @return int|null Reservation ID on success, or null on failure.
     */
    public function confirmReservation($postData, $userId)
    {
        // Extract data from the form submission
        $romID = $postData['romID'];
        $innsjekk = $postData['innsjekk'];
        $utsjekk = $postData['utsjekk'];
        $antallPersoner = $postData['antallPersoner'];
        $bestillingsdato = date('Y-m-d'); // Current date

        // SQL query to insert reservation into the database
        $sql = "INSERT INTO Reservasjon (RomID, BrukerID, Innsjekk, Utsjekk, AntallPersoner, Bestillingsdato) 
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            die("Forberedt uttalelse mislyktes: " . $this->conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("iissis", $romID, $userId, $innsjekk, $utsjekk, $antallPersoner, $bestillingsdato);

        if ($stmt->execute()) {
            $reservationId = $this->conn->insert_id; // Get the auto-incremented ID
            $this->displaySuccessMessage(); // Show a success message
            $stmt->close();
            return $reservationId; // Return the new reservation ID
        } else {
            $this->displayErrorMessage(); // Show an error message
        }

        $stmt->close();
    }
    // Display a success message to the user
    private function displaySuccessMessage()
    {
        echo "<p class='text-green-500 my-4'>Reservasjonen din er bekreftet!</p>";
        echo "
        <script>
            alert('Reservasjonen din er bekreftet! En e-post med reservasjonsdetaljer ble sendt til din mail. Du blir nå omdirigert til dine reservasjoner.');
            window.location.href = '../../Views/Users/UserReservations.php';
        </script>";
    }
    // Display an error message to the user
    private function displayErrorMessage()
    {
        echo "<p class='text-red-500 my-4'>Det oppsto en feil under bestillingen. Prøv igjen senere.</p>";
    }

    /**
     * Fetch reservation details by reservation ID.
     *
     * @param int $reservasjonID Reservation ID.
     * @return array|null Reservation details or null if not found.
     */
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

    /**
     * Fetch reservations for the currently logged-in user.
     *
     * @return array|string List of reservations or an error message.
     */
    public function getReservationsLoggedInUser()
    {
        // Check if a session is already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (!isset($_SESSION['BrukerID'])) {
            return "No user is logged in.";
        }

        $brukerID = $_SESSION['BrukerID']; // Get the user ID from the session

        // Prepare the SQL query to fetch reservations
        $sql = "
            SELECT r.ReservasjonID, r.RomID, r.Innsjekk, r.Utsjekk, r.Bestillingsdato ,rt.RomTypeNavn, rt.Beskrivelse, rt.RoomTypeImage
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
    /**
     * Edit an existing reservation.
     *
     * @param int $reservasjonID Reservation ID.
     * @param int $brukerID User ID.
     * @param int $romID Room ID.
     * @param string $innsjekk Check-in date.
     * @param string $utsjekk Check-out date.
     * @return string Success or error message.
     */
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
    /**
     * Cancel a reservation if conditions are met.
     *
     * @param int $reservationID Reservation ID.
     * @return string Success or error message.
     */
    public function cancelReservation($reservationID)
    {
        // Check if the reservation ID is valid
        if (empty($reservationID)) {
            return "<p class='text-red-500'>Ugyldig reservasjon.</p>"; // Return an error if the ID is invalid
        }

        // Prepare SQL query to fetch the reservation's check-in date
        $query = "SELECT Innsjekk FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $this->conn->prepare($query);

        // Check if the prepared statement was created successfully
        if (!$stmt) {
            die("Forberedt uttalelse mislyktes: " . $this->conn->error); // Stop execution if statement fails
        }

        // Bind the reservation ID to the prepared statement
        $stmt->bind_param('i', $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a reservation was found
        if ($result->num_rows > 0) {
            // Fetch reservation data
            $reservation = $result->fetch_assoc();
            $checkInDate = new DateTime($reservation['Innsjekk']); // Convert check-in date to DateTime object
            $today = new DateTime(); // Get the current date
            $daysDifference = $today->diff($checkInDate)->days; // Calculate the difference in days between today and the check-in date

            // Check if the reservation can be canceled (more than 2 days before check-in)
            if ($checkInDate > $today && $daysDifference > 2) {
                // Proceed with deletion if conditions are met
                return $this->deleteReservationAndRedirect($reservationID);
            } else {
                // Return an error if cancellation is not allowed
                return "<p class='text-red-500'>Reservasjonen kan ikke avbestilles mindre enn 2 dager før innsjekk.</p>";
            }
        } else {
            // Return an error if the reservation is not found
            return "<p class='text-red-500'>Reservasjonen ble ikke funnet.</p>";
        }
    }
    /**
     * Deletes a reservation and redirects the user upon success.
     *
     * @param int $reservationID The ID of the reservation to be deleted.
     * @return string|null A success message or error message if deletion fails.
     */
    private function deleteReservationAndRedirect($reservationID)
    {
        // Prepare the SQL query to delete the reservation
        $sql = "DELETE FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Kunne ikke forberede slettingsuttalelse: " . $this->conn->error);
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

    /**
     * Deletes a reservation by reservation ID.
     *
     * @param int $reservasjonID Reservation ID.
     * @return string Success or error message.
     */
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



    /**
     * Handle a POST request to find available rooms.
     *
     * @return mixed|null Results of the room search or null if not a POST request.
     */
    public function availableRoomPostRequest()
    {
        // Check if the request method is POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve check-in and check-out dates from the form
            $innsjekk = $_POST['innsjekk'];
            $utsjekk = $_POST['utsjekk'];
            // Retrieve the room type if provided, otherwise set it to an empty string
            $romtype = isset($_POST['romtype']) ? $_POST['romtype'] : '';
            // Calculate total number of people (adults + children)
            $antallPersoner = $_POST['antallVoksne'] + $_POST['antallBarn'];

            // Find available rooms using the input data
            return $this->findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner);
        }

        // Return null if the request method is not POST
        return null;
    }

    /**
     * Find available rooms based on input criteria.
     *
     * @param string $innsjekk Check-in date.
     * @param string $utsjekk Check-out date.
     * @param string $romtype Room type ID (optional).
     * @param int $antallPersoner Total number of people.
     * @return mixed Processed results of available rooms.
     */
    private function findAvailableRooms($innsjekk, $utsjekk, $romtype, $antallPersoner)
    {
        // SQL query to fetch rooms not already reserved within the date range
        $sql = "
    SELECT RomID_RomType.RomID, Romtype.RomTypeNavn, Romtype.RoomTypeImage 
    FROM RomID_RomType 
    JOIN Romtype ON RomID_RomType.RomTypeID = Romtype.RomtypeID
    WHERE RomID_RomType.RomID NOT IN (
        SELECT RomID 
        FROM Reservasjon 
        WHERE ('$innsjekk' BETWEEN Innsjekk AND Utsjekk) 
        OR ('$utsjekk' BETWEEN Innsjekk AND Utsjekk)
        OR (Innsjekk BETWEEN '$innsjekk' AND '$utsjekk')
    )
    AND Romtype.RomKapsitet >= $antallPersoner
    ";

        // Add a condition for room type if provided
        if (!empty($romtype)) {
            $sql .= " AND Romtype.RomtypeID = $romtype";
        }

        // Execute the query and process the results
        $result = $this->conn->query($sql);
        return $this->processRoomResults($result, $innsjekk, $utsjekk, $antallPersoner);
    }

    /**
     * Process the results of the room query.
     *
     * @param mysqli_result $result Query result set.
     * @param string $innsjekk Check-in date.
     * @param string $utsjekk Check-out date.
     * @param int $antallPersoner Total number of people.
     * @return string HTML output of the available rooms or an error message.
     */
    private function processRoomResults($result, $innsjekk, $utsjekk, $antallPersoner)
    {
        // Array to store room type counts
        $roomTypeCounts = [];

        // Check if any rooms were found
        if ($result->num_rows > 0) {
            // Loop through the result set
            while ($row = $result->fetch_assoc()) {
                $roomID = $row["RomID"];
                $roomType = $row["RomTypeNavn"];
                $RoomImage = $row["RoomTypeImage"];

                // Increment the count if the room type already exists in the array
                if (isset($roomTypeCounts[$roomType])) {
                    $roomTypeCounts[$roomType]['count']++;
                } else {
                    // Otherwise, initialize the room type in the array
                    $roomTypeCounts[$roomType] = [
                        'count' => 1,
                        'RoomTypeImage' => $RoomImage,
                        'RomID' => $roomID
                    ];
                }
            }

            // Generate the HTML output for the available rooms
            return $this->generateOutput($roomTypeCounts, $innsjekk, $utsjekk, $antallPersoner);
        }

        // Return an error message if no rooms are available
        return "<p class='text-red-500 my-4'>Ingen rom er tilgjengelige for dette antall personer i dette tidsrommet.</p>";
    }

    /**
     * Generate HTML output for available rooms.
     *
     * @param array $roomTypeCounts Array of room type counts and details.
     * @param string $innsjekk Check-in date.
     * @param string $utsjekk Check-out date.
     * @param int $antallPersoner Total number of people.
     * @return string HTML output for available rooms.
     */
    private function generateOutput($roomTypeCounts, $innsjekk, $utsjekk, $antallPersoner)
    {
        // Include the RoomCard component for displaying room details
        include '../../Includes/Components/RoomCard.php';

        // Start generating the HTML output
        $output = "<h2 class='text-xl font-semibold my-4 text-center'>Ledige rom fra $innsjekk til $utsjekk for $antallPersoner personer:</h2>";
        $output .= "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>";

        // Loop through the room types and generate a card for each
        foreach ($roomTypeCounts as $roomType => $data) {
            $count = $data['count'];
            $image = $data['RoomTypeImage'];
            $roomID = $data['RomID'];
            // Generate a room card using the RoomCard component
            $output .= displayRoomCard($roomID, $roomType, $count, $image, $innsjekk, $utsjekk, $antallPersoner);
        }

        // Close the container div
        $output .= "</div>";
        return $output;
    }

    /**
     * Destructor to close the database connection.
     */
    public function __destruct()
    {
        // Close the database connection if it exists
        if ($this->conn) {
            $this->conn->close();
        }
    }



    /**
     * Sends a reservation confirmation email to the user.
     *
     * @param int $reservationId The ID of the reservation.
     * @param string $email The email address of the recipient.
     * @param object $mailer An instance of the Mailer class to send the email.
     * @return string A success or error message.
     */
    public function sendReservationConfirmation($reservationId, $email, $mailer)
    {
        // SQL query to fetch reservation details based on the reservation ID
        $sql = "
        SELECT r.ReservasjonID, r.Innsjekk, r.Utsjekk, r.Bestillingsdato, rt.RomTypeNavn, rt.Beskrivelse
        FROM Reservasjon r
        JOIN RomID_RomType rid ON r.RomID = rid.RomID
        JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
        WHERE r.ReservasjonID = ?
    ";
        $stmt = $this->conn->prepare($sql); // Prepare the SQL statement

        // Bind the reservation ID to the prepared statement as an integer
        $stmt->bind_param("i", $reservationId);
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result of the query
        $reservation = $result->fetch_assoc(); // Fetch the reservation details as an associative array

        // Check if the reservation details were found
        if ($reservation) {
            // Prepare the email subject and message content
            $subject = "Reservasjonsbekreftelse"; // Email subject
            $message = "
            Hei,

            Din reservasjon er bekreftet. Her er detaljene:

            Reservasjon ID: {$reservation['ReservasjonID']}
            Romtype: {$reservation['RomTypeNavn']}
            Beskrivelse: {$reservation['Beskrivelse']}
            Innsjekk: {$reservation['Innsjekk']}
            Utsjekk: {$reservation['Utsjekk']}
            Bestillingsdato: {$reservation['Bestillingsdato']}

            Takk for at du valgte oss.

            Vennlig hilsen,
            Rombooking-system
        ";

            // Use the Mailer instance to send the email
            $mailer->sendEmail($email, $subject, nl2br($message)); // Format the message with HTML line breaks
            return "En e-post med reservasjonsbekreftelse har blitt sendt."; // Success message
        } else {
            // Return an error message if the reservation was not found
            return "Reservasjonen ble ikke funnet.";
        }
    }
}