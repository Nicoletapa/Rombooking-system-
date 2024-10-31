<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php'); // Include the User class

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a User instance for login
    $user = new User($conn, $username);
    $message = $user->login($password);

    // Display the message if there is one (for incorrect password or non-existent user)
    if ($message) {
        echo $message;
    }
}
?>


<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<div class="flex h-screen justify-center items-center">
    <div class="border-2 p-4 pb-0 rounded-md min-h-min shadow-md">
        <h2 class="text-2xl px-2 font-semibold">Login</h2>
        <p class="px-2 pb-2 py-1 text-gray-400 text-sm">Enter your username below to login to your account.</p>
        <form action="Login.php" method="POST" style="background-color: inherit;">
            <label for="username" class="px-2 mb-2 font-semibold">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>
            <label for="password" class="px-2 mb-2 font-semibold">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>
            <button type="submit" class="p-2 w-full rounded-md bg-black text-white mt-2">Login</button>
        </form>
    </div>
</div>