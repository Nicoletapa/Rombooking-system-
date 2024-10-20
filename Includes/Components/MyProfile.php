<?php
session_start();  // Start session

// Assuming a connection to the database is already established
include '../../Includes/config.php';

// Retrieve the BrukerID from the session
if (isset($_SESSION['BrukerID'])) {
    $brukerID = $_SESSION['BrukerID'];
} else {
    echo "No user is logged in.";
    exit;
}

// Count total reservations for the logged-in user
$sql = "SELECT COUNT(*) AS total_reservations FROM Reservasjon WHERE BrukerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $brukerID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_reservations = $row['total_reservations'];

?>

<div id="profile" class="section" style="display: block;">
    <h2 class="text-center text-xl font-semibold">Min Profil</h2>
    <!-- Add profile-related content here -->
    <p class="text-center text-gray-700">Velkommen til din profil.</p>

    <div class="flex flex-col items-center gap-6 h-[95%] p-4">
        <i class="fa-solid fa-user-tie p-24 bg-blue-500 rounded-full"></i>
        <div class="flex justify-between w-full items-center px-1">
            <span class="text-lg"><span class="font-semibold">Brukernavn:
                </span><?php echo $_SESSION['UserName']; ?></span>
            <span class="text-lg"><span class="font-semibold">Email:
                </span>Eksempel@gmail.com</span>

            <span class="text-lg"><span class="font-semibold">Telefon:
                </span><?php echo $_SESSION['TlfNr']; ?></span>
        </div>
        <div class="w-full">
            <div class="w-full h-48 flex gap-4 ">
                <div
                    class="border-l-4 border-green-700 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                    <span class="text-3xl font-medium"><?php echo $total_reservations; ?></span>
                    <span>Antall Reservasjoner</span>
                </div>
                <div
                    class="border-l-4 border-cyan-500  bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                    <span class="text-3xl font-medium">123</span>
                    <span>Placeholder</span>
                </div>
                <div
                    class="border-l-4 border-blue-500 bg-white shadow-md w-1/3 flex-col flex justify-center items-center rounded-md">
                    <span class="text-3xl font-medium">123</span>
                    <span>Placeholder</span>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>