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
            $errors[] = "Password must be at least 8 characters long.";
        }

        // Rule 2: Password must contain at least one uppercase letter (A-Z)
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }

        // Rule 3: Password must contain at least one lowercase letter (a-z)
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }

        // Rule 4: Password must contain at least one digit (0-9)
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one digit.";
        }

        // Rule 5: Password must contain at least one special character (e.g., @, #, $, etc.)
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        // Return the array of error messages (empty if the password is valid)
        return $errors;
    }
}