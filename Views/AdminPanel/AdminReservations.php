<?php include('../../Includes/Layout/Navbar.php'); ?>
<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarAdminPanel.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full min-h-min  p-4">
            <!-- Profile Section -->
            <?php include("../../Includes/Components/AllReservations.php") ?>
        </div>
    </div>
</div>