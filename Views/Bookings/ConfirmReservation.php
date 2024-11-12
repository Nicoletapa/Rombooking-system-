<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include the database configuration
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the posted booking details
    $romID = $_POST['romID'];
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];
    $antallPersoner = $_POST['antallPersoner'];

    // Optional: Use session data to get the current user ID
    session_start();
    $brukerID = $_SESSION['BrukerID']; // Replace with your session variable for user ID

    // Prepare the SQL query to insert the reservation
    $sql = "INSERT INTO Reservasjon (RomID, BrukerID, Innsjekk, Utsjekk, AntallPersoner) VALUES (?, ?, ?, ?, ?)";

    // Use a prepared statement to avoid SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $romID, $brukerID, $innsjekk, $utsjekk, $antallPersoner);

    // Execute the query
    if ($stmt->execute()) {
        // Display confirmation and redirect using JavaScript
        echo "<p class='text-green-500 my-4'>Reservasjonen din er bekreftet!</p>";

        // Add JavaScript to redirect to index.php after 3 seconds
        echo "
        <script>
    alert('Reservasjonen din er bekreftet! Du blir nå omdirigert til startsiden.');
    window.location.href = '../../index.php';
</script>";
    } else {
        // Display an error message
        echo "<p class='text-red-500 my-4'>Det oppsto en feil under bestillingen. Prøv igjen senere.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
