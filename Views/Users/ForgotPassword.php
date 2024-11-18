<?php
session_start();
$message = $_GET['message'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glemt passord</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen justify-center items-center bg-gray-100">
    <div class="border-2 p-6 rounded-md shadow-lg bg-white w-96">
        <h2 class="text-2xl font-semibold text-center mb-4">Glemt passord</h2>
        <p class="text-sm text-gray-500 text-center mb-6">
            Skriv inn din e-postadresse nedenfor for å motta en tilbakestillingslenke.
        </p>
        <?php if ($message): ?>
            <div class="mb-4 text-center">
                <p class="text-sm <?php echo strpos($message, 'ikke') === false ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            </div>
        <?php endif; ?>
        <form action="/Rombooking-system-/Includes/Components/ProcessForgotPassword.php" method="POST"
            class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-post</label>
                <input type="email" name="email" id="email" placeholder="Din e-postadresse" required
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
            </div>
            <button type="submit"
                class="block w-full bg-black text-white text-sm font-semibold py-2 rounded-md hover:opacity-80 transition">
                Send tilbakestillingslenke
            </button>
        </form>
        <div class="text-center mt-4">
            <a href="Login.php" class="text-blue-600 text-sm hover:underline">Tilbake til innlogging</a>
        </div>
    </div>
</body>

</html>