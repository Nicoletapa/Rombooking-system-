    <!-- Nav -->

    <nav class="bg-[#978c8c]">
        <div class="container mx-auto flex items-center h-16 px-4 justify-between">
            <h1 class="text-3xl">motel.no</h1>
            <div class="text-xl">
                <ul class="flex gap-4">
                    <li>
                        <a href="#">Språk</a>
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
                <span class="text-xl"> Hei,
                    <?php echo ucfirst($_SESSION['UserName'])
                        ?>
                    <?php

                        if ($_SESSION['RolleID'] == 2) {
                            echo "(Admin)";
                        };
                        ?>
                </span> <a href="Logout.php" class="bg-[#563635] text-white p-2 px-4 rounded-md">
                    Logg ut
                </a>
                <?php else: ?>
                <a href="login.php" class="bg-[#563635] text-white p-2 px-4 rounded-md">
                    Logg inn
                </a>
                <a href="register.php" class="bg-[#563635] text-white p-2 px-4 rounded-md">
                    Registrer
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>