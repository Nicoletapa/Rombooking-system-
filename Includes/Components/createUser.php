<?php
session_start(); // Start session


include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Admin.php');

// Check if admin is logged in
include '../../Includes/utils/NotAdmin.php';


$message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? 1; // Default to customer role if not specified

    $admin = new Admin($conn, $username, $password, $firstname, $lastname, $phone, $email, $role);

    $message = $admin->register();
}
?>

<html>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

<div class="create-user-section">
    <h2 class="text-xl text-center font-semibold pb-2">Create New User</h2>

    <?php if (!empty($message)): ?>
        <p
            class="text-center <?php echo strpos($message, 'successfully') !== false ? 'text-green-500' : 'text-red-500'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="" class="mt-4 max-w-lg mx-auto">
        <label for="username" class="block font-medium">Username:</label>
        <input type="text" id="username" name="username" required class="w-full border px-2 py-1 mb-3">

        <label for="password" class="block font-medium">Password:</label>
        <input type="password" id="password" name="password" required class="w-full border px-2 py-1 mb-3">

        <label for="firstname" class="block font-medium">First Name:</label>
        <input type="text" id="firstname" name="firstname" required class="w-full border px-2 py-1 mb-3">

        <label for="lastname" class="block font-medium">Last Name:</label>
        <input type="text" id="lastname" name="lastname" required class="w-full border px-2 py-1 mb-3">

        <label for="phone" class="block font-medium">Phone:</label>
        <input type="text" id="phone" name="phone" class="w-full border px-2 py-1 mb-3">

        <label for="email" class="block font-medium">Email:</label>
        <input type="email" id="email" name="email" required class="w-full border px-2 py-1 mb-3">

        <label for="role" class="block font-medium">Role:</label>
        <select id="role" name="role" class="w-full border px-2 py-1 mb-3">
            <option value="1">Customer</option>
            <option value="2">Admin</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4">Create User</button>
    </form>
</div>

</html>