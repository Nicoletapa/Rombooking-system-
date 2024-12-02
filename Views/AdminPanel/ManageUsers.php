<?php include('../../Includes/Layout/Navbar.php'); ?>

<?php
$message = $_GET['message'] ?? null;
?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarAdminPanel.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4 min-h-min">

            <!-- Display the message if present -->
            <?php if ($message): ?>
            <div class="mb-4 text-center">
                <p
                    class="text-lg <?php echo (strpos($message, 'vellykket') !== false) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Profile Section -->
            <?php include("../../Includes/Components/AllUsers.php") ?>
        </div>
    </div>
</div>