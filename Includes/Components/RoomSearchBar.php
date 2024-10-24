<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Room Search</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php
    include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');


    $sql = "SELECT * FROM Romtype";
    $resultRomType = $conn->query($sql);
    ?>
    <div class="w-full min-h-max">

        <form method="POST" action="/Rombooking-system-/Views/Bookings/AvailableReservations.php"
            class="flex flex-row gap-4 items-center">


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
                </select>
            </div>
            <div class="w-1/4 bg-gray-300 text-xl p-4 rounded-md">
                <select name="romtype" class="w-full bg-transparent h-full">
                    <option>Typerom</option>
                    <?php
                    if ($resultRomType->num_rows > 0) {
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
    </div>
</body>

</html>