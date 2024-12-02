<?php

class Helper
{
    /**
     * Validates a password against specific rules.
     *
     * @param string $password The password to validate.
     * @return array An array of error messages, empty if the password is valid.
     */
    public static function validatePassword($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Passordet må være minst 8 tegn langt.";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Passordet må inneholde minst én stor bokstav.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Passordet må inneholde minst én liten bokstav.";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Passordet må inneholde minst ett tall.";
        }

        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "Passordet må inneholde minst ett spesialtegn.";
        }

        return $errors;
    }

    /**
     * Validates a phone number.
     *
     * @param string $phone The phone number to validate.
     * @return string|null An error message if validation fails, null if valid.
     */
    public static function validatePhone($phone)
    {
        if (strlen($phone) > 15) {
            return "Telefonnummer må være på maks 15 tegn.";
        }

        if (!preg_match('/^\d+$/', $phone)) {
            return "Telefonnummeret kan kun inneholde sifre.";
        }

        return null;
    }

    /**
     * Validates an email address.
     *
     * @param string $email The email address to validate.
     * @return string|null An error message if validation fails, null if valid.
     */
    public static function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Ugyldig e-postformat.";
        }

        return null;
    }

    /**
     * Checks if a username is unique in the database.
     *
     * @param mysqli $conn The database connection.
     * @param string $username The username to check.
     * @return bool True if the username is unique, false otherwise.
     */
    public static function isUsernameUnique($conn, $username)
    {
        $stmt = $conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows === 0; // True if no matching username found
    }

    /**
     * Checks if an email is unique in the database.
     *
     * @param mysqli $conn The database connection.
     * @param string $email The email to check.
     * @return bool True if the email is unique, false otherwise.
     */
    public static function isEmailUnique($conn, $email)
    {
        $stmt = $conn->prepare("SELECT * FROM Bruker WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows === 0; // True if no matching email found
    }

    /**
     * Checks if a BrukerID exists in the database.
     *
     * @param mysqli $conn The database connection.
     * @param int $brukerID The BrukerID to check.
     * @return string|null Returns null if the user exists, otherwise an error message.
     */
    public static function doesBrukerIDExist($conn, $brukerID)
    {
        // Prepare the SQL query to check if the BrukerID exists
        $sql = "SELECT BrukerID FROM Bruker WHERE BrukerID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $brukerID); // Bind the BrukerID as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        // If no rows are returned, the user does not exist
        if ($result->num_rows === 0) {
            return "Bruker eksisterer ikke.";
        }

        // If the user exists, return null (no error)
        return null;
    }

    /**
     * Checks if a RomID exists in the database.
     *
     * @param mysqli $conn The database connection.
     * @param int $romID The RomID to check.
     * @return string|null Returns null if the room exists, otherwise an error message.
     */
    public static function doesRomIDExist($conn, $romID)
    {
        // Prepare the SQL query to check if the RomID exists
        $sql = "SELECT RomID FROM RomID_RomType WHERE RomID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $romID); // Bind the RomID as an integer
        $stmt->execute();
        $result = $stmt->get_result();

        // If no rows are returned, the room does not exist
        if ($result->num_rows === 0) {
            return "Rommet eksisterer ikke.";
        }

        // If the room exists, return null (no error)
        return null;
    }
}