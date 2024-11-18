<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php'); // Include the User class

// Fetch the message query parameter if set
$message = $_GET['message'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a User instance for login
    $user = new User($conn, $username);
    $loginMessage = $user->login($password);

    // Display login-related messages (e.g., incorrect password)
    if ($loginMessage) {
        $message = $loginMessage;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen justify-center items-center">
        <div class="border-2 p-6 rounded-md min-h-min shadow-lg bg-white w-96">
            <h2 class="text-2xl px-2 font-semibold text-center">Login</h2>
            <p class="px-2 pb-4 py-1 text-gray-400 text-sm text-center">
                Enter your username and password to log in to your account.
            </p>

            <!-- Display the logout or login message if present -->
            <?php if ($message): ?>
                <div class="mb-4 text-center">
                    <p
                        class="text-sm <?php echo (strpos($message, 'vellykket') !== false || strpos($message, 'oppdatert') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </p>
                </div>
            <?php endif; ?>

            <form action="Login.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block mb-2 font-semibold">Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" required
                        class="border-2 border-gray-300 rounded-md px-3 py-2 w-full focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2 font-semibold">Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" required
                        class="border-2 border-gray-300 rounded-md px-3 py-2 w-full focus:ring-blue-500 focus:border-blue-500">
                </div>
                <button type="submit" class="p-2 w-full rounded-md bg-black text-white font-semibold hover:opacity-80">
                    Login
                </button>
            </form>
            <div class="flex justify-between items-center mt-4">
                <a href="Register.php" class="text-sm text-blue-600 hover:underline">
                    Create an Account
                </a>
                <a href="ForgotPassword.php" class="text-sm text-blue-600 hover:underline">
                    Forgot Password?
                </a>
            </div>
        </div>
    </div>
</body>

</html>