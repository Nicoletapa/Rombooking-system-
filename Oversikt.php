<?php
include 'Includes/config.php'; // Inkluder databasekonfigurasjonen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hent innsjekk- og utsjekksdatoer fra skjemaet
    $innsjekk = $_POST['innsjekk'];
    $utsjekk = $_POST['utsjekk'];

    // Hent valgt antall personer og romtype
    $romtype = $_POST['romtype'];
    $antallPersoner = $_POST['antallPersoner'];

    // SQL for å finne ledige rom med logikk for kapasitet og romtype
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

    $result = $conn->query($sql);

    $output = '';

    if ($result->num_rows > 0) {
        $output .= "<h2 class='text-xl font-semibold my-4'>Ledige rom fra $innsjekk til $utsjekk for $antallPersoner personer:</h2>";
        $output .= "<ul class='list-disc pl-6'>";
        while ($row = $result->fetch_assoc()) {
            echo "Current Directory: " . __DIR__;

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
        $output .= "<p class='text-red-500 my-4'>Ingen rom er tilgjengelige for dette antall personer i dette tidsrommet.</p>";
    }


    // Lukk forbindelsen
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php
    include 'Includes/config.php'; // Include the database connection

    $sql = "SELECT * FROM Romtype";
    $resultRomType = $conn->query($sql);
    ?>
    <div class="w-full min-h-max">
        <form method="POST" action="" class="flex flex-row gap-4 items-center">

            <div class="relative w-1/6">
                <label for="innsjekk" class="absolute -top-6 left-1 font-semibold">Innsjekk dato:</label>
                <div class="bg-gray-300 rounded-md flex flex-col p-4">
                    <input type="date" id="innsjekk" name="innsjekk" required class="bg-transparent relative text-lg">
                </div>
            </div>
            <div class="relative w-1/6">
                <label for="utsjekk" class="absolute -top-6 left-1 font-semibold">Utsjekk dato:</label>
                <div class="bg-gray-300 rounded-md flex flex-col p-4">
                    <input type="date" id="utsjekk" name="utsjekk" required class="relative bg-transparent text-lg">
                </div>
            </div>
            <div class="w-1/4 bg-gray-300 text-xl p-4 rounded-md">
                <select name="antallPersoner" class="w-full bg-transparent h-full">
                    <option value="1">1 Person</option>
                    <option value="2">2 Personer</option>
                    <option value="3">3 Personer</option>
                    <option value="4">4 Personer</option>
                    <!-- Legg til flere / andre alternativer etterhvert..-->
                </select>
            </div>
            <div class="w-1/4 bg-gray-300 text-xl p-4 rounded-md">
                <select name="romtype" class="w-full bg-transparent h-full">
                    <option>Typerom</option>
                    <?php
                    if ($resultRomType->num_rows > 0) {
                        // Output data of each row
                        while ($row = $resultRomType->fetch_assoc()) {
                            echo "<option value='" . $row['RomtypeID'] . "'>" . $row['RomTypeNavn'] . "</option>";
                        }
                    } else {
                        echo "<option>No types available</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="w-48 p-4 text-xl rounded-md bg-[#563635]">
                <button type="submit" value="Sjekk tilgjengelige rom" class="w-full h-full text-white">Søk</button>
            </div>
        </form>
        <!-- Søk resultater -->
        <div class="mt-6">
            <?php
            // Viser resultatet av søket
            if (isset($output)) {
                echo $output;
            }
            ?>
        </div>
    </div>
</body>

</html>