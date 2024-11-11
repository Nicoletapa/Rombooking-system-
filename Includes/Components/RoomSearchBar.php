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
     error_reporting(E_ALL);
     ini_set('display_errors', 1);
    include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
    

    $sql = "SELECT * FROM Romtype";
    $resultRomType = $conn->query($sql);

    // Behold verdiene brukeren inn legger inn etter trykk på søk
    $innsjekk = isset($_POST['innsjekk']) ? $_POST['innsjekk'] : '';
    $utsjekk = isset($_POST['utsjekk']) ? $_POST['utsjekk'] : '';
    $antallVoksne = isset($_POST['antallVoksne']) ? $_POST['antallVoksne'] : 1;
    $antallBarn = isset($_POST['antallBarn']) ? $_POST['antallBarn'] : 0;

    $errors = [];
    
    // Form validation med PHP 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $isValid = true;

        // Validering innsjekk
        if (empty($_POST['innsjekk'])) {
            $errors['innsjekk'] = 'Innsjekk dato er påkrevd.';
            $isValid = false;
        } else {
            $innsjekk = $_POST['innsjekk'];
        }

        // Validering utsjekk
        if (empty($_POST['utsjekk'])) {
            $errors['utsjekk'] = 'Utsjekk dato er påkrevd.';
            $isValid = false;
        } else {
            $utsjekk = $_POST['utsjekk'];
        }

        // Validering antall voksne
        if (empty($_POST['antallVoksne']) || $_POST['antallVoksne'] <= 0) {
            $errors['antallVoksne'] = 'Antall voksne må være minst 1.';
            $isValid = false;
        } else {
            $antallVoksne = $_POST['antallVoksne'];
        }

        // Validering antall barn
        if (!isset($_POST['antallBarn']) || $_POST['antallBarn'] < 0) {
            $errors['antallBarn'] = 'Vennligst fyll inn antall barn.';
            $isValid = false;
        } else {
            $antallBarn = $_POST['antallBarn'];
        }
    }
    ?>
    <div class="w-full min-h-max">
        <form method="POST" action="/Rombooking-system-/Views/Bookings/AvailableReservations.php" class="flex flex-row gap-4 items-center">
            <div class="relative w-1/6">
                <label for="innsjekk" class="absolute -top-6 left-1 font-semibold">Innsjekk dato:</label>
                <div class="bg-gray-300 rounded-md flex flex-col p-4">
                    <input type="date" id="innsjekk" name="innsjekk" class="bg-transparent relative text-lg" value="<?php echo htmlspecialchars($innsjekk)?>" />
                </div>
                <!-- Feilmelding -->
                <?php if (isset($errors['innsjekk'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['innsjekk']; ?></p>
                <?php endif; ?>
            </div>
            <div class="relative w-1/6">
                <label for="utsjekk" class="absolute -top-6 left-1 font-semibold">Utsjekk dato:</label>
                <div class="bg-gray-300 rounded-md flex flex-col p-4">
                    <input type="date" id="utsjekk" name="utsjekk" class="relative bg-transparent text-lg" value="<?php echo htmlspecialchars($utsjekk)?>" />
                </div>
                <!-- Feilmelding -->
                <?php if (isset($errors['utsjekk'])): ?>
                    <p class="text-red-500 text-sm"><?php echo $errors['utsjekk']; ?></p>
                <?php endif; ?>
            </div>
            <div class="relative w-1/6">
                <label for="antallPersoner" class="absolute -top-6 left-1 font-semibold">Personer:</label>
                <!-- Felt for personer -->
                <div class="bg-gray-300 rounded-md flex flex-col p-4">
                    <label for="antallVoksne">Voksne:</label>
                    <input type="number" id="antallVoksne" name="antallVoksne" value="<?php echo $antallVoksne; ?>" class="rounded-sm" />
                    <!-- Feilmelding -->
                    <?php if (isset($errors['antallVoksne'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['antallVoksne']; ?></p>
                    <?php endif; ?>
                    <label for="antallBarn">Barn:</label>
                    <input type="number" id="antallBarn" name="antallBarn" value="<?php echo $antallBarn; ?>" class="rounded-sm" />
                    <!-- Feilmelding -->
                    <?php if (isset($errors['antallBarn'])): ?>
                        <p class="text-red-500 text-sm"><?php echo $errors['antallBarn']; ?></p>
                    <?php endif; ?>
                </div>
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