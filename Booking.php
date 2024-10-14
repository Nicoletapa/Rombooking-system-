<?php
session_start(); // Start session for å få tilgang til $_SESSION

include 'Includes/config.php'; // Inkluder databasekonfigurasjonen

// Sjekk om brukeren er logget inn
if (!isset($_SESSION['BrukerID'])) {
    // Hvis brukeren ikke er logget inn, omdiriger til innloggingssiden
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hent RomID, innsjekk, og utsjekk fra skjemaet, BrukerID fra session
    $romID = $_POST['romID'];
    $brukerID = $_SESSION['BrukerID']; // BrukerID fra innlogget bruker
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];

    // Først: Sjekk om rommet er tilgjengelig i den gitte perioden
    $sqlCheck = "SELECT * FROM Reservasjon
                 WHERE RomID = '$romID'
                 AND (
                     ('$innsjekk' BETWEEN Innsjekk AND Utsjekk)
                     OR ('$utsjekk' BETWEEN Innsjekk AND Utsjekk)
                     OR (Innsjekk BETWEEN '$innsjekk' AND '$utsjekk')
                 )";

    $result = $conn->query($sqlCheck);

    if ($result->num_rows > 0) {
        // Rommet er allerede reservert i den gitte perioden
        echo "Dette rommet er allerede reservert i den valgte perioden. Vennligst velg en annen dato.";
    } else {
        // Hvis rommet er ledig, fortsett med å opprette reservasjonen
        $sql = "INSERT INTO Reservasjon (RomID, BrukerID, Innsjekk, Utsjekk) 
                VALUES ('$romID', '$brukerID', '$innsjekk', '$utsjekk')";

        if ($conn->query($sql) === TRUE) {
            echo "Reservasjon ble opprettet!";
        } else {
            echo "Feil: " . $sql . "<br>" . $conn->error;
        }
    }

    // Lukk forbindelsen
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasjon</title>
</head>

<body>
    <h2>Lag en reservasjon</h2>
    <form method="POST" action="Booking.php">
        <label for="romID">Rom ID:</label><br>
        <input type="number" id="romID" name="romID" required><br><br>

        <label for="innsjekk">Innsjekk dato:</label><br>
        <input type="datetime-local" id="innsjekk" name="innsjekk" required><br><br>

        <label for="utsjekk">Utsjekk dato:</label><br>
        <input type="datetime-local" id="utsjekk" name="utsjekk" required><br><br>

        <input type="submit" value="Lagre reservasjon">
    </form>
</body>

</html>