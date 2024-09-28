<?php
include('config.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the user already exists
    $check_user = "SELECT * FROM users WHERE email='$email' OR username='$username'";
    $result = mysqli_query($conn, $check_user);

    if (mysqli_num_rows($result) > 0) {
        echo "User already exists!";
    } else {
        // Insert new user
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            echo "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
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
    <div class="border-2 p-4 pb-0 rounded-md min-h-min">
        <h2 class="text-2xl px-2 font-semibold">Register</h2>
        <p class="px-2 pb-2 py-1 text-gray-400">Please fill in this form to create an account.</p>
        <form action="register.php" method="POST" style="background-color: inherit;">
            <label for="username" class="px-2 mb-2 font-semibold">Username</label>
            <input type="text" id="username" name="username" placeholder="Username" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="email" class="px-2 mb-2 font-semibold">Email</label>
            <input type="email" id="email" name="email" placeholder="Email" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>

            <label for="password" class="px-2 mb-2 font-semibold">Password</label>
            <input type="password" id="password" name="password" placeholder="Password" required
                style="background-color: inherit; width: 100%; margin-bottom: 10px;"
                class="border-2 rounded-md px-2 py-1"><br>
            <button type="submit" class="p-2 w-full rounded-md bg-black text-white mt-2">Register</button>
        </form>
    </div>
</div>