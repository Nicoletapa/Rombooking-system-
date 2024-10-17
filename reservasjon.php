<?php
include 'Includes/config.php'; // Include database configuration

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the check-in and check-out dates from the form
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];

    // Get the selected number of people and room type
    $romtype = $_POST['romtype'];
    $antallPersoner = $_POST['antallPersoner'];

    // SQL query to find available rooms
    $sql = "SELECT RomID, RomTypeID 
            FROM RomID_RomType 
            WHERE RomID NOT IN (
                SELECT RomID 
                FROM Reservasjon 
                WHERE ('$innsjekk' BETWEEN Innsjekk AND Utsjekk) 
                OR ('$utsjekk' BETWEEN Innsjekk AND Utsjekk)
                OR (Innsjekk BETWEEN '$innsjekk' AND '$utsjekk')
            )
            AND RomTypeID IN (
                SELECT RomtypeID 
                FROM Romtype 
                WHERE RomKapsitet >= $antallPersoner
            )";
    // Execute the SQL query
    $result = $conn->query($sql);

    $output = '';
    // Check if there are available rooms in the result
    if ($result->num_rows > 0) {
        // Create a list of available rooms with roomID and room type, including a booking button for each room
        $output .= "<h2 class='text-xl font-semibold my-4'>Ledige rom fra $innsjekk til $utsjekk for $antallPersoner personer:</h2>";
        $output .= "<ul class='list-disc pl-6'>";
        while ($row = $result->fetch_assoc()) {
            $output .= "<li class='text-lg'>RomID: " . $row["RomID"] . " - RomTypeID: " . $row["RomTypeID"] .
                " <form method='POST' action='Booking.php' style='display:inline;'>
                      <input type='hidden' name='romID' value='" . $row["RomID"] . "'>
                      <input type='hidden' name='innsjekk' value='" . $innsjekk . "'>
                      <input type='hidden' name='utsjekk' value='" . $utsjekk . "'>
                      <button type='submit' class='bg-green-500 text-white px-4 py-2 ml-4 rounded'>Book</button>
                      </form></li>";
        }
        $output .= "</ul>";
    } else {
        // If no rooms are available, display a message to the user
        $output .= "<p class='text-red-500 my-4'>Ingen rom er tilgjengelige for dette antall personer i dette tidsrommet.</p>";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reservasjon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="w-full min-h-max">
        <div class="mt-6">
            <?php
            // Display the search results
            if (isset($output)) {
                echo $output;
            }
            ?>
        </div>
    </div>
</body>
</html>
