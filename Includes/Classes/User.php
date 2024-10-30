<?php
session_start(); // Start the session
include('../../Includes/config.php'); // Include the database configuration file

class User
{
    private $conn;
    private $username;
    private $password;
    private $firstname;
    private $lastname;
    private $phone;
    private $role;

    // Constructor for registration
    public function __construct($conn, $username = null, $password = null, $firstname = null, $lastname = null, $phone = null, $role = null)
    {
        $this->conn = $conn;
        $this->username = mysqli_real_escape_string($conn, $username);
        $this->password = $password ? password_hash($password, PASSWORD_BCRYPT) : null; // Hash password only if provided
        $this->firstname = mysqli_real_escape_string($conn, $firstname);
        $this->lastname = mysqli_real_escape_string($conn, $lastname);
        $this->phone = mysqli_real_escape_string($conn, $phone);
        $this->role = mysqli_real_escape_string($conn, $role);
    }

    // Registration method
    public function register()
    {
        // Check if the user already exists
        $check_user = "SELECT * FROM Bruker WHERE UserName='$this->username'";
        $result = mysqli_query($this->conn, $check_user);

        if (mysqli_num_rows($result) > 0) {
            return "User already exists!";
        } else {
            $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Password, RolleID) 
                      VALUES ('$this->username', '$this->firstname', '$this->lastname', '$this->phone', '$this->password', '$this->role')";
            if (mysqli_query($this->conn, $query)) {
                return "Registration successful! <a href='login.php'>Login here</a>";
            } else {
                return "Error: " . mysqli_error($this->conn);
            }
        }
    }

    // Login method
    public function login($password)
    {
        // Check if the user exists
        $query = "SELECT * FROM Bruker WHERE UserName='$this->username'";
        $result = mysqli_query($this->conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify password
            if (password_verify($password, $user['Password'])) {
                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['RolleID'] = $user['RolleID'];
                $_SESSION['UserName'] = $user['UserName'];
                $_SESSION['BrukerID'] = $user['BrukerID'];
                $_SESSION['TlfNr'] = $user['TlfNr'];

                // Redirect to a protected page
                header('Location: ../../Index.php');
                exit();
            } else {
                return "Incorrect password.";
            }
        } else {
            return "User does not exist.";
        }
    }
}
