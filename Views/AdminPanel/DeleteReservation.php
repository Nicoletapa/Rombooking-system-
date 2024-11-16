<?php

include('../../Includes/Layout/Navbar.php'); ?>
<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarAdminPanel.php'); ?>

        
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <?php include("../../Includes/Components/DeleteReservation.php") ?>
        </div>
    </div>
</div>