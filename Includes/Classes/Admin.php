<?php
// Include the User class to extend its functionalities
include_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/Helper.php');


// Define the Admin class, which extends the User class to inherit its methods and properties
class Admin extends User
{
    // Constructor method for the Admin class
    public function __construct($conn, ?string $username = null, ?string $password = null, ?string $firstname = null, ?string $lastname = null, ?string $phone = null, ?string $email = null, ?string $role = null)
    {
        // Call the parent User class constructor to initialize the inherited properties
        parent::__construct($conn, $username, $password, $firstname, $lastname, $phone, $email, $role);
    }

    // Method to retrieve all users from the database
    public function listUsers()
    {
        $sql = "SELECT * FROM Bruker"; // SQL query to select all rows from the 'Bruker' table
        $result = $this->conn->query($sql); // Execute the query

        $users = []; // Initialize an empty array to store users
        while ($row = $result->fetch_assoc()) { // Loop through each row in the result set
            $users[] = $row; // Add the row to the users array
        }

        return $users; // Return the list of users as an array
    }

    // Method to update a user's details
    public function editUser($userID, $data)
    {
        $sql = "UPDATE Bruker SET Navn = ?, Etternavn = ?, TlfNr = ?, Email = ?, RolleID = ? WHERE BrukerID = ?";
        // Prepare the SQL query for updating a user
        $stmt = $this->conn->prepare($sql);
        // Bind the input data to the query
        $stmt->bind_param(
            "ssssii",
            $data['firstname'],
            $data['lastname'],
            $data['phone'],
            $data['email'],
            $data['role'],
            $userID
        );

        // Execute the query and return true if successful, otherwise false
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Method to remove a user from the database
    public function removeUser($userID)
    {
        // First, delete all reservations associated with the user
        $sqlDeleteReservations = "DELETE FROM Reservasjon WHERE BrukerID = ?";
        $stmtReservations = $this->conn->prepare($sqlDeleteReservations);
        $stmtReservations->bind_param("i", $userID);

        if (!$stmtReservations->execute()) { // Check for errors in deleting reservations
            return "Feil ved sletting av reservasjoner: " . $this->conn->error;
        }

        // Then, delete the user record
        $sqlDeleteUser = "DELETE FROM Bruker WHERE BrukerID = ?";
        $stmtUser = $this->conn->prepare($sqlDeleteUser);
        $stmtUser->bind_param("i", $userID);

        // Return a success message if the user was deleted, otherwise return an error message
        return $stmtUser->execute() ? "Bruker og relaterte reservasjoner slettet vellykket!" : "Feil ved sletting av bruker: " . $this->conn->error;
    }

    // Method to register a new user with validation (overriden method from User class)
    public function register()
    {
        $errors = []; // Initialize an array to collect validation errors

        // Validate password using PasswordValidator
        $passwordErrors = Helper::validatePassword($this->password);
        $errors = array_merge($errors, $passwordErrors); // Add password validation errors to the list

        // Validate the phone number
        $phoneError = Helper::validatePhone($this->phone);
        if ($phoneError) {
            $errors[] = $phoneError;
        }

        // Validate the email address
        $emailError = Helper::validateEmail($this->email);
        if ($emailError) {
            $errors[] = $emailError;
        }

        // Check if username is unique
        if (!Helper::isUsernameUnique($this->conn, $this->username)) {
            $errors[] = "Brukernavnet er allerede i bruk.";
        }

        // Check if email is unique
        if (!Helper::isEmailUnique($this->conn, $this->email)) {
            $errors[] = "E-posten er allerede i bruk.";
        }

        // If there are any validation errors, return them as a single string
        if (!empty($errors)) {
            return $errors;
        }

        // Hash the password for secure storage
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Email, Password, RolleID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $this->username, $this->firstname, $this->lastname, $this->phone, $this->email, $hashedPassword, $this->role);

        // Return a success message if the insertion was successful, otherwise an error message
        if ($stmt->execute()) {
            // Redirect to ManageUsers.php with success message
            header("Location: /Rombooking-system-/Views/AdminPanel/ManageUsers.php?message=" . urlencode("Bruker opprettet vellykket!"));
            exit;
        } else {
            return "Feil: " . $stmt->error;
        }
    }

    // Method to retrieve all reservations with optional filtering
    public function getAllReservations($searchColumn = null, $searchValue = null, $filterActive = 'all')
    {
        $sql = "SELECT * FROM Reservasjon"; // Base SQL query
        $params = []; // Parameters for the query
        $conditions = []; // Conditions for the WHERE clause

        // Add a condition to filter only active reservations
        if ($filterActive === 'active') {
            $conditions[] = "Utsjekk >= NOW()";
        }

        // Add a search condition if search parameters are provided
        if (!empty($searchColumn) && !empty($searchValue)) {
            $conditions[] = "$searchColumn LIKE ?";
            $params[] = "%$searchValue%";
        }

        // If there are conditions, append them to the SQL query
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($sql);

        // Bind the search parameters, if any
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        return $stmt->get_result(); // Return the query result
    }

    // Method to create a reservation for a user
    public function createReservationAdmin($brukerID, $romID, $innsjekk, $utsjekk, $antallPersoner)
    {
        $errors = []; // Initialize an array to collect error messages
        // Check if BrukerID exists
        $brukerError = Helper::doesBrukerIDExist($this->conn, $brukerID);
        if ($brukerError) {
            $errors[] = $brukerError;
        }

        // Check if RomID exists
        $romError = Helper::doesRomIDExist($this->conn, $romID);
        if ($romError) {
            $errors[] = $romError;
        }

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

        // Validate the number of people
        if ($antallPersoner < 0 || $antallPersoner > 4) {
            $errors[] = "Antall personer må være minst 1 eller maks 3.";
        }

        // If there are any validation errors, return them as a single string
        if (!empty($errors)) {
            return implode("<br>", $errors);
        }

        // Insert the reservation into the database
        $sql = "INSERT INTO Reservasjon (BrukerID, RomID, Innsjekk, Utsjekk, AntallPersoner) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iissi", $brukerID, $romID, $innsjekk, $utsjekk, $antallPersoner);

        // Return a success message if the insertion was successful, otherwise an error message
        return $stmt->execute() ? "Reservasjonen ble opprettet!" : "Feil ved oppretting av reservasjonen: " . $this->conn->error;
    }
}