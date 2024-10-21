<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../Includes/config.php'; // Include database configuration
include '../../Includes/Components/RoomCard.php'; // Include the RoomCard component

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the check-in and check-out dates from the form
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];

    // Get the selected number of people and room type
    $romtype = $_POST['romtype'];
    $antallPersoner = $_POST['antallPersoner'];

    // SQL query to find available rooms
    $sql = "SELECT Romtype.RomTypeNavn 
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

    // Execute the SQL query
    $result = $conn->query($sql);

    $roomTypeCounts = [];
    // Check if there are available rooms in the result
    if ($result->num_rows > 0) {
        // Count available room types
        while ($row = $result->fetch_assoc()) {
            $roomType = $row["RomTypeNavn"];
            if (isset($roomTypeCounts[$roomType])) {
                $roomTypeCounts[$roomType]++;
            } else {
                $roomTypeCounts[$roomType] = 1;
            }
        }

        // Create a list of available room types with their counts as cards
        $output = "<h2 class='text-xl font-semibold my-4 text-center'>Ledige rom fra $innsjekk til $utsjekk for $antallPersoner personer:</h2>";
        $output .= "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>";
        foreach ($roomTypeCounts as $roomType => $count) {
            $output .= displayRoomCard($roomType, $count, $innsjekk, $utsjekk, $antallPersoner);
        }
        $output .= "</div>";
    } else {
        // If no rooms are available, display a message to the user
        $output = "<p class='text-red-500 my-4'>Ingen rom er tilgjengelige for dette antall personer i dette tidsrommet.</p>";
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
    <?php include('../../Includes/Layout/Navbar.php'); ?>
    <div class="h-[85vh]">
        <div class=" h-1/2 bg-[#B7B2B2]">
            <div class="container mx-auto flex gap-8 flex justify-center items-center h-full px-4">
                <?php include("../../Includes/Components/RoomSearchBar.php"); ?>
            </div>
            <div class="container mx-auto pb-2">
                <?php
                // Display the search results if available
                if (isset($output)) {
                    echo $output;
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>