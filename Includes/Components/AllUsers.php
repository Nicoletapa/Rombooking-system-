<?php
session_start();  // Start session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming a connection to the database is already established
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');


// Check if the user is an admin
include '../../Includes/utils/NotAdmin.php';
include '../../Includes/utils/NoUserLoggedIn.php';

// Include the Admin class
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');


// Create an Admin object
$admin = new Admin($conn);

// Fetch all users
$users = $admin->listUsers();
?>

<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<div class="users-section">
    <h2 class="text-xl text-center font-semibold pb-2">Alle Brukere</h2>

    <?php if (!empty($users)): ?>
        <table class="table-auto w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">BrukerID</th>
                    <th class="border px-4 py-2">Brukernavn</th>
                    <th class="border px-4 py-2">Navn</th>
                    <th class="border px-4 py-2">Etternavn</th>
                    <th class="border px-4 py-2">Telefon</th>
                    <th class="border px-4 py-2">E-post</th>
                    <th class="border px-4 py-2">RolleID</th>
                    <th class="border px-4 py-2">Handlinger</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['BrukerID']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['UserName']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Navn']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Etternavn']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['TlfNr']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['Email']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($user['RolleID']); ?></td>
                        <td class="border px-4 py-2">
                            <a href="/Rombooking-system-/Views/AdminPanel/UpdateUser.php?BrukerID=<?php echo $user['BrukerID']; ?>"
                                class="text-blue-500 hover:underline">Rediger</a> |
                            <a href="DeleteUser.php?BrukerID=<?php echo $user['BrukerID']; ?>"
                                class="text-red-500 hover:underline">Slett</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Ingen brukere funnet.</p>
    <?php endif; ?>
</div>

</html>