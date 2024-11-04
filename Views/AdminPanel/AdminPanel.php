<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../Includes/config.php';

?>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/cd774ebe5e.js" crossorigin="anonymous"></script>
   
    <?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/Navbar.php');?>
    <div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
   <?php include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Layout/SidebarAdminpanel.php');?>
   <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <?php include("../../Includes/Components/MyProfile.php") ?>
        </div>
    </div>
</div>

    
</html>




