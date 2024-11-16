<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');

class Admin extends User
{
    public function __construct($conn, ?string $username = null, ?string $password = null, ?string $firstname = null, ?string $lastname = null, ?string $phone = null, ?string $email = null, ?string $role = null)
    {
        // Pass all parameters to the parent User constructor
        parent::__construct($conn, $username, $password, $firstname, $lastname, $phone, $email, $role);
    }

    public function listUsers()
    {
        // Retrieve all users from the database
        $sql = "SELECT * FROM Bruker";
        $result = $this->conn->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users; // Return the list of users as an array
    }

    public function editUser($userID, $data)
    {
        // Update user details in the database
        $sql = "UPDATE Bruker SET Navn = ?, Etternavn = ?, TlfNr = ?, Email = ?, RolleID = ? WHERE BrukerID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssii",
            $data['firstname'],
            $data['lastname'],
            $data['phone'],
            $data['email'],
            $data['role'],
            $userID
        );

        if ($stmt->execute()) {
            return "User updated successfully!";
        } else {
            return "Error updating user: " . $this->conn->error;
        }
    }

    public function removeUser($userID)
    {
        // Delete reservations associated with the user
        $sqlDeleteReservations = "DELETE FROM Reservasjon WHERE BrukerID = ?";
        $stmtReservations = $this->conn->prepare($sqlDeleteReservations);
        $stmtReservations->bind_param("i", $userID);

        if (!$stmtReservations->execute()) {
            return "Error deleting reservations: " . $this->conn->error;
        }

        // Now delete the user
        $sqlDeleteUser = "DELETE FROM Bruker WHERE BrukerID = ?";
        $stmtUser = $this->conn->prepare($sqlDeleteUser);
        $stmtUser->bind_param("i", $userID);

        return $stmtUser->execute() ? "User and related reservations deleted successfully!" : "Error deleting user: " . $this->conn->error;
    }
    public function register()
    {

        $errors = [];

        // Validate phone number length
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
            return "User created successfully!"; // Return success message instead of redirecting
        } else {
            return "Error: " . $stmt->error;
        }
    }

  
    public function createReservation($brukerID, $romID, $innsjekk, $utsjekk, $antallPersoner)
    {
        $errors = [];
    
        // Validate check-in and check-out dates
        if (empty($innsjekk)) {
            $errors[] = "Innsjekk dato er påkrevd.";
        }
        if (empty($utsjekk)) {
            $errors[] = "Utsjekk dato er påkrevd.";
        }
        if (new DateTime($innsjekk) >= new DateTime($utsjekk)) {
            $errors[] = "Utsjekk dato må være etter innsjekk dato.";
        }
    
        // Validate number of people
        if ($antallPersoner <= 0) {
            $errors[] = "Antall personer må være minst 1.";
        }
    
        // Return all errors if there are any
        if (!empty($errors)) {
            return implode("<br>", $errors); // Join all errors with line breaks
        }
    
        // Prepare the SQL query to insert the reservation
        $sql = "INSERT INTO Reservasjon (BrukerID, RomID, Innsjekk, Utsjekk, AntallPersoner) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissi", $brukerID, $romID, $innsjekk, $utsjekk, $antallPersoner);
    
        if ($stmt->execute()) {
            return "Reservasjonen ble opprettet!";
        } else {
            return "Feil ved oppretting av reservasjonen: " . $this->conn->error;
        }
    }
}
