<?php
// Include the configuration file for database connection
include('../../Includes/config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $rolle_id = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if the user already exists
    $check_user = "SELECT * FROM Bruker WHERE UserName='$username'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        // User already exists
        echo "User already exists!";
    } else {
        // Insert new user
        $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Password, RolleID) 
                  VALUES ('$username', '$firstname', '$lastname', '$phone', '$hashed_password', '$rolle_id')";
        if (mysqli_query($conn, $query)) {
            // Registration successful

            echo "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            // Error inserting user
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
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