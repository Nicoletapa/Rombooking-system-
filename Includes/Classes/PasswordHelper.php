<?php

// Define the PasswordHelper class to handle password-related validations
class PasswordHelper
{
    /**
     * Validates a password against specific rules.
     *
     * @param string $password The password to validate.
     * @return array An array of error messages, empty if the password is valid.
     */
    public static function validate($password)
    {
        // Initialize an empty array to store validation error messages
        $errors = [];

        // Rule 1: Password must be at least 8 characters long
        if (strlen($password) < 8) {
            $errors[] = "Passordet må være minst 8 tegn langt.";
        }

        // Rule 2: Password must contain at least one uppercase letter (A-Z)
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Passordet må inneholde minst én stor bokstav.";
        }

        // Rule 3: Password must contain at least one lowercase letter (a-z)
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Passordet må inneholde minst én liten bokstav.";
        }

        // Rule 4: Password must contain at least one digit (0-9)
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Passordet må inneholde minst ett tall.";
        }

        // Rule 5: Password must contain at least one special character (e.g., @, #, $, etc.)
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "Passordet må inneholde minst ett spesialtegn.";
        }

        // Return the array of error messages (empty if the password is valid)
        return $errors;
    }
}