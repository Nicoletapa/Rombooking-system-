<?php
session_start();

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');
include $_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NoUserLoggedIn.php';
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/utils/NotAdmin.php');

$message = [];
$feedbackClass = ''; // Initialize to avoid undefined variable notices

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 1; // Default to customer role if not specified

    $admin = new Admin($conn, $username, $password, $firstname, $lastname, $phone, $email, $role);

    // Capture the result of the register method
    $result = $admin->register();

    if (is_array($result)) {
        // If validation errors are returned as an array
        $message = $result;
        $feedbackClass = 'text-red-600';
    } else {
        // If registration is successful, store the success message
        $message[] = $result;
        $feedbackClass = 'text-green-600';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Opprett ny bruker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-[85vh] justify-center items-center">
        <div class="bg-white border-2 p-6 rounded-md shadow-lg w-2/5">
            <h2 class="text-2xl font-semibold text-center mb-4">Opprett ny bruker</h2>
            <p class="text-sm text-gray-500 text-center mb-6">

                Vennligst fyll ut dette skjemaet for å opprette en ny brukerkonto. </p>


            <?php if (!empty($message)): ?>
            <div class="<?php echo $feedbackClass; ?> text-center mb-2">
                <?php foreach ($message as $msg): ?>
                <p><?php echo htmlspecialchars($msg); ?></p>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Brukernavn</label>
                        <input type="text" id="username" name="username" placeholder="Username" required
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    </div>
                    <div>

                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefonnummer</label>

                        <input type="text" id="phone" name="phone" placeholder="Phone Number" required
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    </div>
                    <div>
                        <label for="firstname" class="block text-sm font-medium text-gray-700 mb-1">Fornavn</label>
                        <input type="text" id="firstname" name="firstname" placeholder="First Name" required
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    </div>
                    <div>
                        <label for="lastname" class="block text-sm font-medium text-gray-700 mb-1">Etternavn</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Last Name" required
                            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                    </div>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" placeholder="Email" required
                        class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Passord</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rolle</label>
                    <select id="role" name="role" required
                        class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2">
                        <option value="1">Bruker</option>
                        <option value="2">Admin</option>
                    </select>
                </div>
                <button type="submit"
                    class="block w-full bg-black text-white text-sm font-semibold py-2 rounded-md hover:opacity-80 transition">

                    Opprett bruker
                </button>
            </form>
            <div class="text-center mt-4">
                <a href="/Rombooking-system-/Views/AdminPanel/ManageUsers.php"
                    class="text-blue-600 text-sm hover:underline">Tilbake til dashbord</a>

            </div>
        </div>
    </div>
</body>

</html>