<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Motel.no</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <!-- Søk seksjon -->
    <?php include('./Includes/Layout/Navbar.php'); ?>
    <div class="h-[85vh]">
        <div class="h-1/2 bg-[#B7B2B2]">
            <div class="container mx-auto flex gap-8 flex justify-center items-center h-full px-4">
                <?php include('./Includes/Components/RoomSearchBar.php') ?>
            </div>
        </div>
        <!-- 3 Typer rom seksjon -->
        <div class="h-3/5">
            <div class="container mx-auto px-4 h-full items-center justify-center flex flex-col">
                <h2 class="text-3xl mb-8">Room Types</h2>
                <div class="flex w-full gap-4 h-2/3 lg:flex-row flex-col items-center">
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md">
                            <img src="Public/Images/Enkeltrom.avif" class="w-full h-full object-cover" alt="test" />
                        </div>
                        <span class="text-2xl">Enkelt Rom</span>
                    </div>
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md">
                            <img src="Public/Images/Dobbeltrom.avif" class="w-full h-full object-cover" alt="test" />
                        </div>
                        <span class="text-2xl">Dobbeltrom</span>
                    </div>
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md">
                            <img src="Public/Images/JuniorSuite.jpg" class="w-full h-full object-cover" alt="test" />
                        </div>
                        <span class="text-2xl">Junior Suite</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>