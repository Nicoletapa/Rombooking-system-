<?php include('../../Includes/Layout/Navbar.php'); ?>

<div class="min-h-[85vh] container mx-auto">
    <div class="flex h-[90%]">
        <?php include('../../Includes/Layout/SidebarProfileNav.php'); ?>

        <!-- Content Section -->
        <div class="bg-[#B7B2B2] w-full p-4">
            <!-- Profile Section -->
            <?php include("../../Includes/Components/MyProfile.php") ?>
        </div>
    </div>
</div>

<script>
    // Function to show the selected section
    function showSection(section) {
        // Hide all sections
        document.querySelectorAll('.section').forEach(function(el) {
            el.style.display = 'none';
        });

        // Show the selected section
        document.getElementById(section).style.display = 'block';
    }
</script>