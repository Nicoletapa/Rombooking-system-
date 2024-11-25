<?php include('../../Includes/Layout/Navbar.php'); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarAdminPanel.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <?php include("../../Includes/Components/EditUser.php") ?>
        </div>
    </div>
</div>