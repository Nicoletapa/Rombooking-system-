<?php include('../../Includes/Layout/Navbar.php'); ?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <nav class="w-1/4 flex flex-col gap-2 font-medium p-4">
            <!-- Min Profil -->
            <a href="javascript:void(0);" class="flex gap-2 hover:bg-gray-200 rounded-md"
                onclick="showSection('profile')">
                <i class="text-center fa-solid fa-house h-full flex justify-center items-center w-5"></i><span
                    class="text-xl">Min Profil</span>
            </a>

            <!-- Mine Bestillinger -->
            <a href="javascript:void(0);" class="flex gap-2 hover:bg-gray-200 rounded-md"
                onclick="showSection('orders')">
                <i class="fa-solid fa-file-invoice h-full flex justify-center items-center w-5"></i><span
                    class="text-xl">Mine Bestillinger</span>
            </a>

            <!-- Bytt Passord -->
            <a href="javascript:void(0);" class="flex gap-2 hover:bg-gray-200 rounded-md"
                onclick="showSection('password')">
                <i class="fa-solid fa-key h-full flex justify-center items-center w-5"></i><span class="text-xl">Bytt
                    Passord</span>
            </a>
        </nav>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <div id="profile" class="section" style="display: block;">
                <h2 class="text-center text-xl font-semibold">Min Profil</h2>
                <!-- Add profile-related content here -->
                <p class="text-center text-gray-700">Velkommen til din profil.</p>

                <div class="flex flex-col items-center gap-6 h-[95%] p-4">
                    <i class="fa-solid fa-user-tie p-24 bg-blue-500 rounded-full"></i>
                    <div class="flex justify-between w-full items-center">
                        <span class="text-lg"><span class="font-semibold">Brukernavn:
                            </span><?php echo
                                    $_SESSION['UserName']; ?></span>
                        <span class="text-lg"><span class="font-semibold">Email:
                            </span>Eksempel@gmail.com</span>

                        <span class="text-lg"><span class="font-semibold">Telefon:
                            </span><?php echo $_SESSION['TlfNr']; ?></span>
                    </div>
                    <div class="w-full">
                        <div class="w-full h-48 flex gap-4 ">
                            <div
                                class="border-l-4 border-green-700 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span class="text-3xl font-medium">123</span>
                                <span>Antall Reservasjoner</span>
                            </div>
                            <div
                                class="border-l-4 border-cyan-500  bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span class="text-3xl font-medium">123</span>
                                <span>Antall Reservasjoner</span>
                            </div>
                            <div
                                class="border-l-4 border-blue-500 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                                <span class="text-3xl font-medium">123</span>
                                <span>Antall Reservasjoner</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <?php include("../../Includes/Components/UserReservations.php") ?>

            <!-- Change Password Section -->
            <div id="password" class="section" style="display: none;">
                <h2>Bytt Passord</h2>
                <!-- Add password change form here -->
                <form action="change_password.php" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br>

                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required><br>

                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required><br>

                    <button type="submit">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to show the selected section
    function showSection(section) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(function(el) {
            el.style.display = 'none';
        });

        // Show the selected section
        document.getElementById(section).style.display = 'block';
    }
</script>