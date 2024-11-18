<?php
session_start(); // Start or continue a session
session_unset(); // Remove all session variables
session_destroy(); // Completely destroy the session

// Redirect to login with a logout success message
header('Location: /Rombooking-System-/Views/Users/Login.php?message=Utlogging vellykket.');
exit();
