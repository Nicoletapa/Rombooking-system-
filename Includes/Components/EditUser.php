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

<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

<div class="edit-user-section">
    <h2 class="text-xl text-center font-semibold pb-2">Rediger Bruker</h2>

    <form method="POST" action="">
        <label for="firstname">Fornavn:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['Navn']); ?>"
            required>

        <label for="lastname">Etternavn:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['Etternavn']); ?>"
            required>

        <label for="phone">Telefon:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['TlfNr']); ?>">

        <label for="email">E-post:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>

        <label for="role">Rolle:</label>
        <select id="role" name="role">
            <option value="1" <?php echo $user['RolleID'] == 1 ? 'selected' : ''; ?>>Bruker</option>
            <option value="2" <?php echo $user['RolleID'] == 2 ? 'selected' : ''; ?>>Admin</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4">Lagre</button>
    </form>
</div>

</html>