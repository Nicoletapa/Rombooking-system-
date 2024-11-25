<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');

$admin = new Admin($conn);
$users = $admin->listUsers();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alle Brukere</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center py-6">
        <div class="bg-white shadow-lg rounded-md p-6 w-full max-w-5xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Alle Brukere</h2>
                <a href="/Rombooking-system-/Views/AdminPanel/addUser.php"
                    class="bg-blue-600 text-white text-sm font-medium px-5 py-2 rounded-md shadow hover:bg-blue-700 transition">
                    + Legg til ny bruker
                </a>
            </div>

            <?php if (!empty($users)): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300 bg-white shadow rounded-lg">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">BrukerID</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Brukernavn</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Navn</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Etternavn</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Telefon</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">E-post</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Rolle</th>
                                <th class="border px-4 py-2 text-left text-sm font-medium text-gray-700">Handlinger</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['BrukerID']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['UserName']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['Navn']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['Etternavn']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['TlfNr']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['Email']); ?></td>
                                    <td class="border px-4 py-2 text-sm text-gray-800">
                                        <?php echo htmlspecialchars($user['RolleID'] == 1 ? 'Bruker' : 'Admin'); ?></td>
                                    <td class="border text-sm text-gray-800">
                                        <div class="flex items-center flex-col w-full">
                                            <a href="/Rombooking-system-/Views/AdminPanel/UpdateUser.php?BrukerID=<?php echo $user['BrukerID']; ?>"
                                                class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm font-medium shadow hover:bg-blue-600 transition w-full text-center">
                                                Rediger
                                            </a>
                                            <a href="DeleteUser.php?BrukerID=<?php echo $user['BrukerID']; ?>"
                                                class="bg-red-500 text-white px-3 py-1 rounded-md text-sm font-medium shadow hover:bg-red-600 transition w-full text-center">
                                                Slett
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-700 mt-4">Ingen brukere funnet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>