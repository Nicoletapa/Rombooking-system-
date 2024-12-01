<?php
// Include the PasswordHelper class for validating passwords
require_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/PasswordHelper.php');

// Define the PasswordManager class to handle password-related functionalities
class PasswordManager
{
    // Private property to hold the database connection
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    /**
     * Sends a password reset link to the user's email address.
     *
     * @param string $email The user's email address.
     * @param object $mailer An instance of a Mailer class to handle sending emails.
     * @return string A message indicating success or failure.
     */
    public function sendResetLink($email, $mailer)
    {
        // Prepare a query to check if the email exists in the 'Bruker' table
        $stmt = $this->conn->prepare("SELECT BrukerID FROM Bruker WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $userId = null; // Ensure the variable is initialized

        // If the email exists, proceed with generating the reset token
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId);
            $stmt->fetch();

            // Generate a secure random token and set its expiration time to 1 hour
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Insert the token and its expiration into the PasswordReset table
            $stmt = $this->conn->prepare("INSERT INTO PasswordReset (BrukerID, Token, Expires) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $token, $expires);
            $stmt->execute();

            // Create a password reset link using the generated token
            $resetLink = "http://localhost/Rombooking-system-/Views/Users/ResetPassword.php?token=" . $token;

            // Send the reset link to the user's email
            $mailer->sendEmail($email, "Reset Password", "Click this link to reset your password: $resetLink");
            return "E-posten med tilbakestillingslenke har blitt sendt."; // Success message
        } else {
            return "E-posten finnes ikke."; // Error if the email is not found
        }
    }

    /**
     * Resets the user's password using a token.
     *
     * @param string $token The reset token provided by the user.
     * @param string $newPassword The new password entered by the user.
     * @param string $confirmPassword The password confirmation entered by the user.
     * @return string A message indicating success or failure.
     */
    public function resetPassword($token, $newPassword, $confirmPassword)
    {
        // Validate the new password using PasswordHelper
        $passwordErrors = PasswordHelper::validate($newPassword);

        // Check if the new password matches the confirmation password
        if ($newPassword !== $confirmPassword) {
            $passwordErrors[] = "Passordene stemmer ikke overens."; // Error if passwords don't match
        }

        // If there are validation errors, return them as a single string
        if (!empty($passwordErrors)) {
            return implode("<br>", $passwordErrors);
        }

        // Hash the new password for secure storage
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Check if the token exists and is still valid
        $stmt = $this->conn->prepare("SELECT BrukerID FROM PasswordReset WHERE Token = ? AND Expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        $userId = null; // Ensure the variable is initialized

        // If the token is valid, proceed with updating the password
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId);
            $stmt->fetch();

            // Update the user's password in the Bruker table
            $stmt = $this->conn->prepare("UPDATE Bruker SET Password = ? WHERE BrukerID = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();

            // Delete the used token from the PasswordReset table
            $stmt = $this->conn->prepare("DELETE FROM PasswordReset WHERE Token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            return "Passordet ditt har blitt oppdatert. Logg inn med det nye passordet."; // Success message
        } else {
            return "Token er ugyldig eller utløpt."; // Error if the token is invalid or expired
        }
    }
}