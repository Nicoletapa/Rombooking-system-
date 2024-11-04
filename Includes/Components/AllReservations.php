<?php
session_start();  // Start session
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Assuming a connection to the database is already established
include '../../Includes/config.php';

// Check if the user is an admin
include '../../Includes/utils/NotAdmin.php';
include '../../Includes/utils/NoUserLoggedIn.php';

// Fetch the search input if provided
$search_column = isset($_GET['search_column']) ? $_GET['search_column'] : 'ReservasjonID';
$search_value = isset($_GET['search_value']) ? $_GET['search_value'] : '';

// Fetch all reservations with room type details, optionally filtered by the selected column
$sql = "
    SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn
    FROM Reservasjon r
    JOIN RomID_RomType rid ON r.RomID = rid.RomID
    JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
";
if ($search_value) {
    $sql .= " WHERE r.$search_column = ?";
}
$stmt = $conn->prepare($sql);
if ($search_value) {
    $stmt->bind_param("s", $search_value);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<html>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<div class="reservations-section">
    <h2 class="text-xl text-center font-semibold pb-2">Alle Reservasjoner</h2>

    <!-- Search Form -->
    <form method="GET" action="">
    <select name="search_column">
        <option value="ReservasjonID">ReservasjonID</option>
        <option value="BrukerID">BrukerID</option>
        <option value="RomID">RomID</option>
    </select>
    <input type="text" name="search_value" placeholder="Søk verdi" value="<?php echo htmlspecialchars($search_value); ?>">
    <button type="submit">Søk</button>
</form>

    <?php if ($result->num_rows > 0): ?>
    <table class="table-auto w-full border-collapse ">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ReservasjonID</th>
                <th class="border px-4 py-2">BrukerID</th>
                <th class="border px-4 py-2">Romnummer</th>
               
                <th class="border px-4 py-2">Innsjekk</th>
                <th class="border px-4 py-2">Utsjekk</th>
                <th class="border px-4 py-2">Handlinger</th>

            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['ReservasjonID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['BrukerID']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['RomID']); ?></td>
                
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['Innsjekk']); ?></td>
                <td class="border px-4 py-2"><?php echo htmlspecialchars($row['Utsjekk']); ?></td>
                <td class="border px-4 py-2">
                    <a href="ReservationDetails.php?ReservasjonID=<?php echo $row['ReservasjonID']; ?>" class="text-blue-500 hover:underline">Vis detaljer</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="text-center">Ingen reservasjoner funnet.</p>
    <?php endif; ?>
</div>
</html>