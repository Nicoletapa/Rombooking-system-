<?php
// Enable error reporting and display for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the PasswordHelper class
require_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/PasswordHelper.php');

// Start a session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database configuration file
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');

class User
{
    // Database connection and user-related properties
    protected $conn;
    protected $brukerID;
    protected $username;
    protected $password;
    protected $firstname;
    protected $lastname;
    protected $phone;
    protected $email;
    protected $role;

    /**
     * Constructor to initialize a User instance.
     *
     * @param mysqli $conn The database connection.
     * @param string|null $username The username (optional).
     * @param string|null $password The password (optional).
     * @param string|null $firstname The first name (optional).
     * @param string|null $lastname The last name (optional).
     * @param string|null $phone The phone number (optional).
     * @param string|null $email The email address (optional).
     * @param string|null $role The user role (optional).
     */
    public function __construct($conn, ?string $username = null, ?string $password = null, ?string $firstname = null, ?string $lastname = null, ?string $phone = null, ?string $email = null, ?string $role = null)
    {
        $this->conn = $conn; // Assign database connection
        $this->username = $username ?? ''; // Assign or set default empty string
        $this->password = $password; // Assign password
        $this->firstname = $firstname ?? ''; // Assign or default to empty string
        $this->lastname = $lastname ?? ''; // Assign or default to empty string
        $this->phone = $phone ?? ''; // Assign or default to empty string
        $this->email = $email ?? ''; // Assign or default to empty string
        $this->role = $role ?? ''; // Assign or default to empty string
    }

    /**
     * Registers a new user in the system.
     *
     * @return string|null Error messages if validation fails, otherwise redirects to the login page.
     */
    public function register()
    {
        $errors = []; // Initialize an array to collect validation errors

        // Validate password using PasswordValidator
        $passwordErrors = PasswordHelper::validate($this->password);
        $errors = array_merge($errors, $passwordErrors); // Add password validation errors to the list

        // Validate phone number length
        if (strlen($this->phone) > 15) {
            $errors[] = "Telefonnummer må være på maks 15 tegn.";
        }
         // Validate phone number format (only digits allowed)
    if (!preg_match('/^\d+$/', $this->phone)) {
        $errors[] = "Telefonnummeret kan kun inneholde sifre.";
    }

        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Ugyldig e-postformat.";
        }

        // Check if the user already exists in the database
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $this->username); // Bind the username parameter
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Fetch the result set

        if ($result->num_rows > 0) {
            $errors[] = "Bruker eksisterer allerede."; // Add error if user exists
        }

        // Return errors if any are found
        if (!empty($errors)) {
            return implode($errors); // Join errors with line breaks and return
        }

        // If no errors, hash the password for secure storage
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Default role as "Customer" (RolleID = 1)
        $defaultRole = 1;

        // Prepare SQL query to insert the new user
        $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Email, Password, RolleID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $this->username, $this->firstname, $this->lastname, $this->phone, $this->email, $hashedPassword, $defaultRole);

        // Execute the query and handle success or failure
        if ($stmt->execute()) {
            header('Location: Login.php'); // Redirect to login page on success
            exit;
        } else {
            return "Feil: " . $stmt->error; // Return error message on failure
        }
    }

    /**
     * Authenticates a user with the provided password.
     *
     * @param string $password The user's password.
     * @return string|null Error message if login fails, otherwise redirects to the appropriate page.
     */
    public function login($password)
    {
        // Prepare SQL query to retrieve the user's data
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $this->username); // Bind the username parameter
        $stmt->execute(); // Execute the query
        $user = $stmt->get_result()->fetch_assoc(); // Fetch user data as an associative array

        // Check if user exists
        if (!$user) {
            return "Feil brukernavn eller passord.";
        }

        // Check if the account is locked due to too many failed attempts
        if ($user['FailedLoginAttempts'] >= 3 && (time() - strtotime($user['LastFailedLogin'])) < 3600) {
            return "For mange mislykkede påloggingsforsøk. Vennligst prøv igjen om en time.";
        }

        // Verify the provided password against the stored hashed password
        if (password_verify($password, $user['Password'])) {
            // Reset failed login attempts on success
            $stmt = $this->conn->prepare("UPDATE Bruker SET FailedLoginAttempts = 0 WHERE UserName = ?");
            $stmt->bind_param("s", $this->username);
            $stmt->execute();

            // Set session variables to indicate a successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['RolleID'] = $user['RolleID'];
            $_SESSION['firstname'] = $user['Navn'];
            $_SESSION['lastname'] = $user['Etternavn'];
            $_SESSION['UserName'] = $user['UserName'];
            $_SESSION['BrukerID'] = $user['BrukerID'];
            $_SESSION['TlfNr'] = $user['TlfNr'];
            $_SESSION['email'] = $user['Email'];

            // Redirect based on the user's role
            if ($user['RolleID'] == 2) { // Admin role
                header('Location: /RomBooking-System-/Views/AdminPanel/AdminPanel.php');
            } else { // Regular user role
                header('Location: ../../Index.php');
            }
            exit; // Stop further script execution after redirection
        } else {
            // Increment failed login attempts on incorrect password
            $stmt = $this->conn->prepare("UPDATE Bruker SET FailedLoginAttempts = FailedLoginAttempts + 1, LastFailedLogin = NOW() WHERE UserName = ?");
            $stmt->bind_param("s", $this->username);
            $stmt->execute();

            return "Feil brukernavn eller passord.";
        }
    }

    /**
     * Changes the password for the current user.
     *
     * @param string $current_password The current password.
     * @param string $new_password The new password.
     * @return string Message indicating success or failure.
     */
    public function changePassword($current_password, $new_password)
    {
        $errors = []; // Initialize an array to collect errors

        // Prepare SQL query to fetch user data
        $sql = "SELECT * FROM Bruker WHERE UserName = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->username); // Bind the username parameter
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Fetch the result set

        // Check if a matching user was found
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc(); // Fetch user data

            // Verify the current password
            if (!password_verify($current_password, $user['Password'])) {
                $errors[] = "Nåværende passord er feil.";
            }

            // Validate the new password using PasswordHelper
            $passwordErrors = PasswordHelper::validate($new_password);
            $errors = array_merge($errors, $passwordErrors); // Add validation errors to the list

            if (!empty($errors)) {
                return implode("<br>", $errors); // Return all errors joined with line breaks
            }

            // Hash the new password
            $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $update_sql = "UPDATE Bruker SET Password = ? WHERE UserName = ?";
            $update_stmt = $this->conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password_hashed, $this->username);

            if ($update_stmt->execute()) {
                return "Password changed successfully!";
            } else {
                return "Feil ved oppdatering av passord: " . $this->conn->error;
            }
        } else {
            return "Bruker ikke funnet.";
        }
    }

    /**
     * Retrieves user information by their ID.
     *
     * @param int $userID The user's ID.
     * @return array|null The user's information as an associative array or null if not found.
     */
    public function getUserById($userID)
    {
        // Prepare SQL query to fetch user data by ID
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE BrukerID = ?");
        $stmt->bind_param("i", $userID); // Bind the user ID parameter
        $stmt->execute(); // Execute the query
        return $stmt->get_result()->fetch_assoc(); // Fetch and return user data as an associative array
    }
}