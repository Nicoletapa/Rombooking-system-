<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php'); // Include the database configuration file

class User
{
    protected $conn;
    protected $brukerID;
    protected $username;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $phone;
    protected $email;
    protected $role;

    // Constructor for registration
    public function __construct($conn, ?string $username = null, ?string $password = null, ?string $firstname = null, ?string $lastname = null, ?string $phone = null, ?string $email = null, ?string $role = null)
    {
        $this->conn = $conn;
        $this->username = $username ?? '';
        $this->password = $password;
        $this->firstname = $firstname ?? '';
        $this->lastname = $lastname ?? '';
        $this->phone = $phone ?? '';
        $this->email = $email ?? '';
        $this->role = $role ?? '';
    }


    public function register()
    {
        $errors = [];
        // Validate phone number length (assuming the column length is 15)
        if (strlen($this->phone) > 15) {
            $errors[] = "Phone number must be at most 15 characters long.";
        }
        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }
        // Check password criteria
        if (strlen($this->password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        if (!preg_match('/[A-Z]/', $this->password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $this->password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[0-9]/', $this->password)) {
            $errors[] = "Password must contain at least one digit.";
        }
        if (!preg_match('/[\W]/', $this->password)) {
            $errors[] = "Password must contain at least one special character.";
        }




        // Check if the user already exists
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "User already exists.";
        }

        // Return all errors if there are any
        if (!empty($errors)) {
            return implode("<br>", $errors); // Join all errors with line breaks
        }

        // If no errors, hash the password and proceed with registration
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Email, Password, RolleID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $this->username, $this->firstname, $this->lastname, $this->phone, $this->email, $hashedPassword, $this->role);

        if ($stmt->execute()) {
            header('Location: Login.php');
            exit;
        } else {
            return "Error: " . $stmt->error;
        }
    }

    public function getTotalReservations($brukerID)
    {
        $sql = "SELECT COUNT(*) AS total_reservations FROM Reservasjon WHERE BrukerID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $brukerID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row['total_reservations'] ?? 0;
    }


    // Login method - Accepts only the password as parameter
    public function login($password)
    {
        // Retrieve the user's data from the database
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // Check if user exists
        if (!$user) {
            return "Incorrect username or password.";
        }

        // Check for lockout due to too many failed login attempts
        if ($user['FailedLoginAttempts'] >= 3 && (time() - strtotime($user['LastFailedLogin'])) < 3600) {
            return "Too many failed login attempts. Please try again in one hour.";
        }

        // Verify the entered password with the stored hashed password
        if (password_verify($password, $user['Password'])) {
            // Reset failed attempts on successful login
            $stmt = $this->conn->prepare("UPDATE Bruker SET FailedLoginAttempts = 0 WHERE UserName = ?");
            $stmt->bind_param("s", $this->username);
            $stmt->execute();

            // Set session variables upon successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['RolleID'] = $user['RolleID'];
            $_SESSION['firstname'] = $user['Navn'];
            $_SESSION['lastname'] = $user['Etternavn'];
            $_SESSION['UserName'] = $user['UserName'];
            $_SESSION['BrukerID'] = $user['BrukerID'];
            $_SESSION['TlfNr'] = $user['TlfNr'];
            $_SESSION['email'] = $user['Email'];

            // Redirect based on role
            if ($user['RolleID'] == 2) {
                header('Location: /RomBooking-System-/Views/AdminPanel/AdminPanel.php');
            } else {
                header('Location: ../../Index.php');
            }
            exit;
        } else {
            // Increment failed attempts and set last failed login timestamp
            $stmt = $this->conn->prepare("UPDATE Bruker SET FailedLoginAttempts = FailedLoginAttempts + 1, LastFailedLogin = NOW() WHERE UserName = ?");
            $stmt->bind_param("s", $this->username);
            $stmt->execute();

            return "Incorrect username or password.";
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
