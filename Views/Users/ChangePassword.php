<?php
// Assuming a connection to the database is already established
include '../../Includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Fetch the user from the database
    $sql = "SELECT * FROM Bruker WHERE UserName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify current password (assuming passwords are hashed)
        if (password_verify($current_password, $user['Password'])) {
            // Hash the new password
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_sql = "UPDATE Bruker SET Password = ? WHERE UserName = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password_hashed, $username);

            if ($update_stmt->execute()) {
                echo "Password changed successfully!";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<form action="ChangePassword.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required><br>

    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password" required><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br>

    <button type="submit">Change Password</button>
</form>