    <!-- Nav -->

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Document</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/cd774ebe5e.js" crossorigin="anonymous"></script>
    </head>
    <style>
        html {
            overflow-y: scroll;
        }
    </style>

    <nav class="bg-[#978c8c] w-full">
        <div class="container mx-auto flex items-center h-16 px-4 justify-between">
            <a href="/RomBooking-System-/">
                <h1 class="text-3xl">motel.no</h1>
            </a>
            <div class="text-xl">
                <ul class="flex gap-4">
                    <li>
                        <a href="/RomBooking-System-/Views/AdminPanel/AdminPanel.php">AdminPanel</a>
                    </li>
                    <li>
                        <a href="#">Valuta</a>
                    </li>
                    <li>
                        <a href="#">Kundeservice</a>
                    </li>
                </ul>
            </div>
            <?php
            session_start();
            ?>

            <div class="flex gap-4 items-center">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <a href="/RomBooking-System-/Views/Users/UserPanel.php" class="text-xl"> Hei,
                        <?php echo ucfirst($_SESSION['UserName'])
                        ?>
                        <?php

                        if ($_SESSION['RolleID'] == 2) {
                            echo "(Admin)";
                            //legge til include fil
                        };
                        ?>
                    </a> <a href="/RomBooking-System-/Views/Users/Logout.php"
                        class="bg-[#563635] text-white p-2 px-4 rounded-md">
                        Logg ut
                    </a>
                <?php else: ?>
                    <a href="/RomBooking-System-/Views/Users/Login.php" class="bg-[#563635] text-white p-2 px-4 rounded-md">
                        Logg inn
                    </a>
                    <a href="/RomBooking-System-/Views/Users/Register.php"
                        class="bg-[#563635] text-white p-2 px-4 rounded-md">
                        Registrer
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>