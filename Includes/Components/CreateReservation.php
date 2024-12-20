<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');

$message = '';
// Retrieve the form data and create a new reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukerID = $_POST['brukerID'] ?? '';
    $romID = $_POST['romID'] ?? '';
    $innsjekk = $_POST['innsjekk'] ?? '';
    $utsjekk = $_POST['utsjekk'] ?? '';
    $antallPersoner = $_POST['antallPersoner'] ?? 1;

    $reservation = new Admin($conn);
    $message = $reservation->createReservationAdmin($brukerID, $romID, $innsjekk, $utsjekk, $antallPersoner);

    // Redirect to AdminReservations page if successful
    if (strpos($message, 'opprettet') !== false) {
        echo "<script>
            setTimeout(function() {
                window.location.href = '../../Views/AdminPanel/AdminReservations.php';
            }, 3000);
        </script>";
    }
}

?>

<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<div class="create-reservation-section">
    <h2 class="text-xl text-center font-semibold pb-2">Opprett ny reservasjon</h2>
    <?php if (!empty($message)): ?>
    <p class="text-center <?php echo strpos($message, 'opprettet') !== false ? 'text-green-500' : 'text-red-500'; ?>">
        <?php echo $message; ?>
    </p>
    <?php endif; ?>
    <form method="POST" action="" class="mt-4 max-w-lg mx-auto">
        <label for="brukerID" class="block font-medium">BrukerID:</label>
        <input type="text" id="brukerID" name="brukerID"
            value="<?php echo htmlspecialchars($_POST['brukerID'] ?? ''); ?>" required
            class="w-full border px-2 py-1 mb-3">

        <label for="romID" class="block font-medium">RomID:</label>
        <input type="text" id="romID" name="romID" value="<?php echo htmlspecialchars($_POST['romID'] ?? ''); ?>"
            required class="w-full border px-2 py-1 mb-3">

        <label for="innsjekk" class="block font-medium">Innsjekk:</label>
        <input type="datetime-local" id="innsjekk" name="innsjekk"
            value="<?php echo htmlspecialchars($_POST['innsjekk'] ?? ''); ?>" required
            class="w-full border px-2 py-1 mb-3">

        <label for="utsjekk" class="block font-medium">Utsjekk:</label>
        <input type="datetime-local" id="utsjekk" name="utsjekk"
            value="<?php echo htmlspecialchars($_POST['utsjekk'] ?? ''); ?>" required
            class="w-full border px-2 py-1 mb-3">

        <label for="antallPersoner" class="block font-medium">Antall Personer:</label>
        <input type="number" id="antallPersoner" name="antallPersoner"
            value="<?php echo htmlspecialchars($_POST['antallPersoner'] ?? '1'); ?>" required
            class="w-full border px-2 py-1 mb-3">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4">Create Reservation</button>
    </form>
</div>

</html>