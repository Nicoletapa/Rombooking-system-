<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include DB config

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationID = $_POST['reservation_id'];

    // Validate reservation ID
    if (!empty($reservationID)) {
        $query = "SELECT Innsjekk FROM Reservasjon WHERE ReservasjonID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $reservationID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $reservation = $result->fetch_assoc();
            $checkInDate = new DateTime($reservation['Innsjekk']);
            $today = new DateTime();
            $daysDifference = $today->diff($checkInDate)->days;

            if ($checkInDate > $today && $daysDifference > 2) {
                $deleteQuery = "DELETE FROM Reservasjon WHERE ReservasjonID = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param('i', $reservationID);
                $deleteStmt->execute();

                if ($deleteStmt->affected_rows > 0) {
                    echo "<p class='text-green-500'>Reservasjonen ble avbestilt.</p>";
                } else {
                    echo "<p class='text-red-500'>Kunne ikke avbestille reservasjonen. Vennligst ta kontakt med vår kundeservice.</p>";
                }
            } else {
                echo "<p class='text-red-500'>Reservasjonen kan ikke avbestilles mindre enn 2 dager før innsjekk.</p>";
            }
        } else {
            echo "<p class='text-red-500'>Reservasjonen ble ikke funnet.</p>";
        }
    } else {
        echo "<p class='text-red-500'>Ugyldig reservasjon.</p>";
    }

    if ($stmt->execute()) {
        // Display confirmation and redirect using JavaScript
        echo "<p class='text-green-500 my-4'>Reservasjonen har blitt avbestilt.</p>";

        // Add JavaScript to redirect to index.php after 3 seconds
        echo "
        <script>
            alert('Reservasjonen har blitt avbestilt. Du blir nå tatt tilbake til dine reservasjoner.');
            window.location.href = '/Rombooking-system-/Views/Users/UserReservations.php';
        </script>";
    } else {
        // Display an error message
        echo "<p class='text-red-500 my-4'>Det oppsto en feil under avbestillingen. Prøv igjen senere.</p>";
    }

    $stmt->close();
    $conn->close();
}


?>