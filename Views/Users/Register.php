<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php'); // Include the User class

error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a new User instance and attempt to register
    $user = new User($conn, $username, $password, $firstname, $lastname, $phone, $email);
    $message = $user->register();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen justify-center items-center">


        <div class="bg-white border-2 p-6 rounded-md shadow-lg w-96">
            <?php if (isset($message)) : ?>
            <div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            <h2 class="text-2xl font-semibold text-center mb-4">Registrer</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
            Vennligst fyll ut dette skjemaet for å opprette en konto..
            </p>
            <form action="register.php" method="POST" class="space-y-4">
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

                <button type="submit"
                    class="block w-full bg-black text-white text-sm font-semibold py-2 rounded-md hover:opacity-80 transition">
                    Registrer
                </button>
            </form>
            <div class="text-center mt-4">
                <a href="Login.php" class="text-blue-600 text-sm hover:underline">Har du allerede en konto? Logg inn</a>
            </div>
        </div>
    </div>
</body>

</html>