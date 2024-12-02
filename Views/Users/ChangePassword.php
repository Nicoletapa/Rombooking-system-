<?php
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php'); // Include the User class

// Initialize message variable
$message = "";

// Retrieve the username from the session
if (isset($_SESSION['UserName'])) {
    $username = $_SESSION['UserName'];
    $user = new User($conn, $username); // Instantiate User with the current session username
} else {
    $message = "No user is logged in.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($message)) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Use the changePassword method and capture the message
    $message = $user->changePassword($current_password, $new_password);
}
?>

<!-- Include your HTML structure here -->
<?php include('../../Includes/Layout/Navbar.php'); ?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarProfileNav.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-2">
            <!-- Change Password Section -->
            <div id="password" class="section border-gray-500 p-2 rounded-md">
                <h2 class="text-xl text-center font-semibold">Bytt Passord</h2>
                <p class="px-2 pb-2 text-gray-700 text-sm text-center">Legg inn nytt passord under for å oppdatere
                    passord</p>

                <!-- Display message -->
                <?php if (!empty($message)): ?>
                <div
                    class="text-center mb-4 p-2 <?php echo strpos($message, 'successfully') !== false ? 'bg-green-500' : 'bg-red-500'; ?> text-white">
                    <?php echo ($message); ?>
                </div><br>
                <?php endif; ?>

                <div class="flex justify-center items-center">
                    <!-- Add password change form here -->
                    <form action="" method="post">
                        <label class="px-2 mb-2 font-semibold" for="username">Brukernavn:</label>
                        <input type="text" id="username" name="username" style="background-color: inherit;"
                            class="border-2 rounded-md px-2 py-1 w-full mb-[10px] opacity-50"
                            value="<?php echo htmlspecialchars($username); ?>" disabled><br>

                        <label class="px-2 mb-2 font-semibold" for="current_password">Nåværende passord:</label>
                        <input type="password" id="current_password" name="current_password" required
                            style="background-color: inherit;"
                            class="border-2 rounded-md px-2 py-1 w-full mb-[10px]"><br>

                        <label class="px-2 mb-2 font-semibold" for="new_password">Nytt passord:</label>
                        <input type="password" id="new_password" name="new_password" required
                            style="background-color: inherit;"
                            class="border-2 rounded-md px-2 py-1 w-full mb-[10px]"><br>

                        <button class="p-2 w-full rounded-md text-white mt-2 bg-[#563635] hover:opacity-80"
                            type="submit">Bytt passord</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>