<?php
session_start();  // Start session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');
// Create an Admin object
$admin = new Admin($conn);
// Get the user ID from the query string
$userID = $_GET['BrukerID'] ?? null;
// Ensure the user ID is provided
if (!$userID) {
    die("Bruker-ID er påkrevd.");
}
// Fetch user details
$user = $admin->getUserById($userID);
if (!$user) {
    die("Bruker ikke funnet.");
}
// Process the deletion if confirmed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    $result = $admin->removeUser($userID);
    echo "<p>$result</p>";
    echo '<a href="ManageUsers.php" class="text-blue-500 hover:underline">Tilbake til brukerlisten</a>';
    exit;
}
?>

<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<div class="delete-user-section">
    <h2 class="text-xl text-center font-semibold pb-2">Bekreft Sletting av Bruker</h2>

    <p class="text-center">Er du sikker på at du vil slette denne brukeren?</p>
    <table class="table-auto w-full border-collapse mt-4">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">BrukerID</th>
                <th class="border px-4 py-2">Brukernavn</th>
                <th class="border px-4 py-2">Navn</th>
                <th class="border px-4 py-2">Etternavn</th>
                <th class="border px-4 py-2">E-post</th>
                <th class="border px-4 py-2">RolleID</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['BrukerID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['UserName']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Navn']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Etternavn']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Email']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($user['RolleID']); ?></td>
            </tr>
        </tbody>
    </table>

    <form method="POST" action="" class="mt-4 text-center">
        <button type="submit" name="confirm" class="bg-red-500 text-white px-4 py-2 mr-2">Ja, slett</button>
        <a href="ManageUsers.php" class="bg-gray-500 text-white px-4 py-2">Nei, gå tilbake</a>
    </form>
</div>

</html>