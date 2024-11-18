<?php
class PasswordManager
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    public function sendResetLink($email, $mailer)
    {
        $stmt = $this->conn->prepare("SELECT BrukerID FROM Bruker WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        $userId = null; // Ensure the variable is initialized
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId);
            $stmt->fetch();

            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $stmt = $this->conn->prepare("INSERT INTO PasswordReset (BrukerID, Token, Expires) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $token, $expires);
            $stmt->execute();
            $resetLink = "http://localhost/Rombooking-system-/Views/Users/ResetPassword.php?token=" . $token;

            $mailer->sendEmail($email, "Reset Password", "Click this link to reset your password: $resetLink");
            return "E-posten med tilbakestillingslenke har blitt sendt.";
        } else {
            return "E-posten finnes ikke.";
        }
    }

    public function resetPassword($token, $newPassword, $confirmPassword)
    {
        if ($newPassword !== $confirmPassword) {
            return "Passordene stemmer ikke overens.";
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $stmt = $this->conn->prepare("SELECT BrukerID FROM PasswordReset WHERE Token = ? AND Expires > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        $userId = null; // Ensure the variable is initialized
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId);
            $stmt->fetch();

            $stmt = $this->conn->prepare("UPDATE Bruker SET Password = ? WHERE BrukerID = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();

            $stmt = $this->conn->prepare("DELETE FROM PasswordReset WHERE Token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            return "Passordet ditt har blitt oppdatert. Logg inn med det nye passordet.";
        } else {
            return "Token er ugyldig eller utløpt.";
        }
    }
}
