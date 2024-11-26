<?php

class PasswordHelper
{
    public static function validate($password)
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one digit.";
        }
        if (!preg_match('/[\W]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        return $errors;
    }
}