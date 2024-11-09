<?php
// Include the configuration file for database connection
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php'); // Include the User class

error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $role = $_POST['role'];



    // Create a new User instance and attempt to register
    $user = new User($conn, $username, $password, $firstname, $lastname, $phone, $email, $role);


    $message = $user->register();

    echo $message;
}
?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<div class="flex h-screen justify-center items-center">
    <div class="border-2 p-4 pb-0 rounded-md min-h-min">
        <h2 class="text-2xl px-2 font-semibold">Register</h2>
        <p class="px-2 pb-2 py-1 text-gray-400">Please fill in this form to create an account.</p>
        <form action="Register.php" method="POST" style="background-color: inherit;">
            <label for="username" class="px-2 mb-2 font-semibold">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="firstname" class="px-2 mb-2 font-semibold">First Name</label>
            <input type="text" id="firstname" name="firstname" placeholder="First Name" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="lastname" class="px-2 mb-2 font-semibold">Last Name</label>
            <input type="text" id="lastname" name="lastname" placeholder="Last Name" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="phone" class="px-2 mb-2 font-semibold">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="Phone Number" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="email" class="px-2 mb-2 font-semibold">Email</label>
            <input type="text" id="email" name="email" placeholder="Email" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="password" class="px-2 mb-2 font-semibold">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="role" class="px-2 mb-2 font-semibold">Role</label>
            <select id="role" name="role" required style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1">
                <option value="1">Customer</option>
                <option value="2">Admin</option>
            </select><br>

            <button type="submit" class="p-2 w-full rounded-md bg-black text-white mt-2">Register</button>
        </form>
    </div>
</div>