<?php include('../../Includes/Layout/Navbar.php');
?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarProfileNav.php'); ?>

        <!-- Content Section -->

        <div class="bg-[#B7B2B2] w-full min-h-min p-4">

       
            <?php
            if (isset($_GET['message']) && $_GET['message'] === 'success') {
                echo "<p class='text-green-300'>Reservasjonen ble avbestilt.</p>";
            }
            ?>

            <!-- Profile Section -->
            <?php include("../../Includes/Components/MyReservations.php") ?>
        </div>
    </div>
</div>