<?php
session_start(); // Start the session
include('../../Includes/config.php'); // Include the database configuration file

class User
{
    private $conn;
    private $brukerID;
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
    // Password change method
    public function changePassword($current_password, $new_password)
    {
        // Fetch the user from the database
        $sql = "SELECT * FROM Bruker WHERE UserName = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify current password
            if (password_verify($current_password, $user['Password'])) {
                // Hash the new password
                $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the password in the database
                $update_sql = "UPDATE Bruker SET Password = ? WHERE UserName = ?";
                $update_stmt = $this->conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $new_password_hashed, $this->username);

                if ($update_stmt->execute()) {
                    return "Password changed successfully!";
                } else {
                    return "Error updating password: " . $this->conn->error;
                }
            } else {
                return "Current password is incorrect.";
            }
        } else {
            return "User not found.";
        }
    }

    // Method to fetch reservations for the logged-in user
    public function getLoggedInUserReservations()
    {
        session_start(); // Ensure session is started

        // Check if the user is logged in
        if (!isset($_SESSION['BrukerID'])) {
            return "No user is logged in.";
        }

        $brukerID = $_SESSION['BrukerID']; // Get the user ID from the session

        // Prepare the SQL query to fetch reservations
        $sql = "
        SELECT r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse
        FROM Reservasjon r
        JOIN RomID_RomType rid ON r.RomID = rid.RomID
        JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
        WHERE r.BrukerID = ?
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $brukerID);
        $stmt->execute();
        $result = $stmt->get_result();

        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }

        $stmt->close();
        return $reservations;
    }
}
