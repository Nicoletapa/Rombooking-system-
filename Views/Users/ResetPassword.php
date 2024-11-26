<?php
session_start();
$token = $_GET['token'] ?? '';
$error_message = $_GET['error'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tilbakestill passord</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen justify-center items-center bg-gray-100">
    <div class="border-2 p-6 rounded-md shadow-lg bg-white w-96">
        <?php if (isset($error_message)) : ?>
        <div class="bg-red-500 text-white p-4 rounded-md shadow-md text-center">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        <h2 class="text-2xl font-semibold text-center mb-4">Tilbakestill passord</h2>
        <p class="text-sm text-gray-500 text-center mb-6">
            Opprett et nytt passord ved å fylle ut feltene nedenfor.
        </p>
        <?php if (!$token): ?>
        <div class="text-center text-red-600 mb-4">
            Token mangler eller er ugyldig. Vennligst prøv igjen.
        </div>
        <?php else: ?>
        <form action="/Rombooking-system-/Includes/Handlers/ProcessResetPassword.php" method="POST" class="space-y-4">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nytt passord</label>
                <input type="password" id="password" name="password" placeholder="Nytt passord" required
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">Bekreft
                    passord</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Bekreft passord"
                    required
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2">
            </div>
            <button type="submit"
                class="block w-full bg-black text-white text-sm font-semibold py-2 rounded-md hover:opacity-80 transition">
                Oppdater passord
            </button>
        </form>
        <?php endif; ?>
        <div class="text-center mt-4">
            <a href="/Rombooking-system-/Views/Users/ForgotPassword.php" class="text-blue-600 text-sm hover:underline">
                Tilbake til Glemt Passord
            </a>
        </div>
    </div>
</body>

</html>