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
        <div class="h-1/2 bg-[url('Public/Images/By.png')] bg-cover bg-center opacity-80">
            <div class="container mx-auto flex gap-8 flex justify-center items-center h-full px-4">
                <?php include('./Includes/Components/RoomSearchBar.php') ?>
            </div>
        </div>
        <!-- 3 Typer rom seksjon -->
        <div class="h-3/5">
            <div class="container mx-auto px-4 h-full items-center justify-center flex flex-col">
                <h2 class="text-3xl mb-8">Romtyper</h2>
                <div class="flex w-full gap-4 h-2/3 lg:flex-row flex-col items-center">
                    <!-- Enkeltrom -->
                    <div class="flex flex-col h-full w-full text-center">
                        <div onclick="showModal('enkeltrom')" class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md cursor-pointer hover:opacity-80 hover:shadow-lg transform transition-transform">
                            <img src="Public/Images/Enkeltrom.avif" class="w-full h-full object-cover" alt="Enkeltrom" />
                        </div>
                        <span class="text-2xl">Enkeltrom</span>
                    </div>
                    <!-- Dobbeltrom -->
                    <div class="flex flex-col h-full w-full text-center">
                        <div onclick="showModal('dobbeltrom')" class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md cursor-pointer hover:opacity-80 hover:shadow-lg transform transition-transform">
                            <img src="Public/Images/Dobbeltrom.avif" class="w-full h-full object-cover" alt="Dobbeltrom" />
                        </div>
                        <span class="text-2xl">Dobbeltrom</span>
                    </div>
                    <!-- Junior Suite -->
                    <div class="flex flex-col h-full w-full text-center">
                        <div onclick="showModal('juniorSuite')" class="bg-gray-500 rounded-3xl h-full aspect-square overflow-hidden shadow-md cursor-pointer hover:opacity-80 hover:shadow-lg transform transition-transform">
                            <img src="Public/Images/JuniorSuite.jpg" class="w-full h-full object-cover" alt="Junior Suite" />
                        </div>
                        <span class="text-2xl">Junior Suite</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modaler -->
     <div onclick="closeModal('enkeltrom')" id="enkeltrom" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col lg:flex-row w-[90%] max-w-4xl  relative" onclick="event.stopPropagation()">
            <div class="lg:w-1/2 w-full">
                <img src="Public/Images/Enkeltrom.avif" alt="Enkeltrom" class="w-full h-auto rounded-lg object-cover" />
            </div>
            <div class="lg:w-1/2 w-full p-4">
                <h3 id="enkeltromTitle" class="text-2xl font-bold mb-4">Enkeltrom</h3>
                <p class="mb-2">Et enkelt komfortabelt rom for én person.</p>
                <p class="mb-2">Pris: NOK 550 per natt</p>
                <p class="mb-2">Maks antall gjester: 1</p>
            </div>
            <button onclick="closeModal('enkeltrom')" class="absolute bottom-4 right-4 bg-[#563635] text-white px-4 py-2 rounded">Lukk</button>
        </div>
     </div>

     <div onclick="closeModal('dobbeltrom')" id="dobbeltrom" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col lg:flex-row w-[90%] max-w-4xl relative" onclick="event.stopPropagation()">
            <div class="lg:w-1/2 w-full">
                <img src="Public/Images/Dobbeltrom.avif" alt="Dobbeltrom" class="w-full h-auto rounded-lg object-cover" />
            </div>
            <div class="lg:w-1/2 w-full p-4">
                <h3 id="dobbeltTitle" class="text-2xl font-bold mb-4">Dobbeltrom</h3>
                <p class="mb-2">Et komfortabelt rom for to personer.</p>
                <p class="mb-2">Pris: NOK 750 per natt</p>
                <p class="mb-2">Maks antall gjester: 2</p>
            </div>
            <button onclick="closeModal('dobbeltrom')" class="absolute bottom-4 right-4 bg-[#563635] text-white px-4 py-2 rounded">Lukk</button>
        </div>
     </div>

     <div onclick="closeModal('juniorSuite')" id="juniorSuite" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg flex flex-col lg:flex-row w-[90%] max-w-4xl relative" onclick="event.stopPropagation()">
            <div class="lg:w-1/2 w-full">
                <img src="Public/Images/JuniorSuite.jpg" alt="Junior Suite" class="w-full h-auto rounded-lg object-cover" />
            </div>
            <div class="lg:w-1/2 w-full p-4">
                <h3 id="juniorSuiteTitle" class="text-2xl font-bold mb-4">Junior Suite</h3>
                <p class="mb-2">Et komfortabelt rom for en familie på fire.</p>
                <p class="mb-2">Pris: NOK 900 per natt</p>
                <p class="mb-2">Maks antall gjester: 4</p>
            </div>
            <button onclick="closeModal('juniorSuite')" class="absolute bottom-4 right-4 bg-[#563635] text-white px-4 py-2 rounded">Lukk</button>
        </div>
     </div>

    <!-- JavaScript for å håndtere modaler -->
    <script>
        function showModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden')
        }
    </script>
</body>

</html>