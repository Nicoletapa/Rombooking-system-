<?php
session_start();  // Start session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');
include '../../Includes/utils/NotAdmin.php';
include '../../Includes/utils/NoUserLoggedIn.php';

// Create an Admin object
$admin = new Admin($conn);

// Get the user ID from the query string
$userID = $_GET['BrukerID'] ?? null;

if (!$userID) {
    die("User ID is required.");
}

// Fetch user details if editing
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT * FROM Bruker WHERE BrukerID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user) {
        die("User not found.");
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'role' => $_POST['role']
    ];

    // Call the editUser method
    $result = $admin->editUser($userID, $data);

    // Display the result message
    echo "<p>$result</p>";
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediger Bruker</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Rediger Bruker</h2>

        <form method="POST" action="" class="space-y-6">
            <!-- First Name -->
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700">Fornavn:</label>
                <input type="text" id="firstname" name="firstname"
                    value="<?php echo htmlspecialchars($user['Navn']); ?>" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Last Name -->
            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700">Etternavn:</label>
                <input type="text" id="lastname" name="lastname"
                    value="<?php echo htmlspecialchars($user['Etternavn']); ?>" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Telefon:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['TlfNr']); ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-post:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rolle:</label>
                <select id="role" name="role"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" <?php echo $user['RolleID'] == 1 ? 'selected' : ''; ?>>Bruker</option>
                    <option value="2" <?php echo $user['RolleID'] == 2 ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit"
                    class="inline-block bg-blue-500 text-white font-medium py-2 px-6 rounded-md shadow-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:outline-none">
                    Lagre
                </button>
            </div>
        </form>
    </div>
</body>

</html>