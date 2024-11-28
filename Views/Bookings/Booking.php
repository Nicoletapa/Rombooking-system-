<?php
session_start();


// Get the posted booking details
$romID = $_POST['romID'];
$romNavn = $_POST['romNavn'];
$innsjekk = $_POST['innsjekk'];
$utsjekk = $_POST['utsjekk'];
$antallPersoner = $_POST['antallPersoner'];
$email = $_SESSION['email'];



?>
<?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/Navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bekreft Reservasjon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="container mx-auto py-8">
        <h2 class="text-2xl font-bold text-center mb-8">Bekreft Reservasjon</h2>
        <div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
            <!-- Display the booking details without allowing them to be changed -->
            <p><strong>Romtype:</strong> <?php echo htmlspecialchars($romNavn); ?></p>
            <p><strong>Innsjekk:</strong> <?php echo htmlspecialchars($innsjekk); ?></p>
            <p><strong>Utsjekk:</strong> <?php echo htmlspecialchars($utsjekk); ?></p>
            <p><strong>Antall Personer:</strong> <?php echo htmlspecialchars($antallPersoner); ?></p>
           

            <!-- Hidden form to submit the booking -->
            <form id="bookingForm" method="POST" action="/Rombooking-system-/Includes/Handlers/ConfirmReservation.php">
                
                <input type="hidden" name="romID" value="<?php echo htmlspecialchars($romID); ?>">
                <input type="hidden" name="innsjekk" value="<?php echo htmlspecialchars($innsjekk); ?>">
                <input type="hidden" name="utsjekk" value="<?php echo htmlspecialchars($utsjekk); ?>">
                <input type="hidden" name="antallPersoner" value="<?php echo htmlspecialchars($antallPersoner);
                 ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
<div class="flex justify-between">
                
                 <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded mt-4">
        <a href="/Rombooking-system-/index.php">Avbryt reservasjon</a>
    </button>
    <button type="button" id="openModal"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4">
                    Bekreft Reservasjon
                </button>
</div>

            </form>
         
    
   


           
        </div>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md mx-auto">
            <h3 class="text-xl font-semibold mb-4">Er du sikker på at du vil bekrefte denne reservasjonen?</h3>
            <div class="flex justify-end space-x-4">
                <button id="confirmButton"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Ja, Bekreft</button>
                <button id="cancelButton"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">Avbryt</button>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle modal behavior -->
    <script>
    const modal = document.getElementById('confirmationModal');
    const openModalButton = document.getElementById('openModal');
    const confirmButton = document.getElementById('confirmButton');
    const cancelButton = document.getElementById('cancelButton');
    const bookingForm = document.getElementById('bookingForm');

    // Show the modal when clicking the "Bekreft Reservasjon" button
    openModalButton.addEventListener('click', function() {
        modal.classList.remove('hidden');
    });

    // When the user clicks on "Ja, Bekreft", submit the form
    confirmButton.addEventListener('click', function() {
        
        bookingForm.submit();
    });

    // When the user clicks on "Avbryt", close the modal
    cancelButton.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
</script>
</body>

</html>