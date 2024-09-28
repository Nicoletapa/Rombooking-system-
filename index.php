<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <!-- Søk seksjon -->
    <?php include('./Layout/Navbar.php'); ?>
    <div class="h-[85vh]">
        <div class="h-1/2 bg-[#B7B2B2]">
            <div class="container mx-auto flex gap-8 flex justify-center items-center h-full px-6">
                <div class="w-1/3 bg-gray-300 p-4 rounded-md">
                    <h1 class="text-xl">Innsjekking - utsjekking</h1>
                </div>
                <div class="w-1/3 bg-gray-300 text-xl p-4 rounded-md">
                    <select class="w-full bg-transparent h-full">
                        <option>2 Voksne - 0 Barn</option>
                    </select>
                </div>
                <div class="w-1/3 bg-gray-300 text-xl p-4 rounded-md">
                    <select class="w-full bg-transparent h-full">
                        <option>Typerom</option>
                    </select>
                </div>
                <div class="w-36 p-4 text-xl rounded-md bg-[#563635]">
                    <button class="w-full h-full text-white">Søk</button>
                </div>
            </div>
        </div>
        <!-- 3 Typer rom seksjon -->
        <div class="h-3/5">
            <div class="container mx-auto px-4 h-full items-center justify-center flex flex-col">
                <h2 class="text-3xl mb-8">Room Types</h2>
                <!-- Header added here -->
                <div class="flex w-full gap-4 h-2/3">
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full"></div>
                        <span class="text-2xl">Enkelt Rom</span>
                    </div>
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full"></div>
                        <span class="text-2xl">Dobbeltrom</span>
                    </div>
                    <div class="flex flex-col h-full w-full text-center">
                        <div class="bg-gray-500 rounded-3xl h-full"></div>
                        <span class="text-2xl">Junior Suite</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>