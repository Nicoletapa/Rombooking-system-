Here’s the provided code with detailed comments explaining each line for better understanding:

<?php
// Include the User class to extend its functionalities
include_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');

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
            return "Error deleting reservations: " . $this->conn->error;
        }

        // Then, delete the user record
        $sqlDeleteUser = "DELETE FROM Bruker WHERE BrukerID = ?";
        $stmtUser = $this->conn->prepare($sqlDeleteUser);
        $stmtUser->bind_param("i", $userID);

        // Return a success message if the user was deleted, otherwise return an error message
        return $stmtUser->execute() ? "User and related reservations deleted successfully!" : "Error deleting user: " . $this->conn->error;
    }

    // Method to register a new user with validation
    public function register()
    {
        $errors = []; // Initialize an array to collect error messages

        // Validate phone number length
        if (strlen($this->phone) > 15) {
            $errors[] = "Phone number must be at most 15 characters long.";
        }

        // Validate email format
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        // Validate password complexity
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

        // Check if a user with the same username already exists
        $stmt = $this->conn->prepare("SELECT * FROM Bruker WHERE UserName = ?");
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "User already exists.";
        }

        // If there are any validation errors, return them as a single string
        if (!empty($errors)) {
            return implode("<br>", $errors);
        }

        // Hash the password for secure storage
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $query = "INSERT INTO Bruker (UserName, Navn, Etternavn, TlfNr, Email, Password, RolleID) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssssi", $this->username, $this->firstname, $this->lastname, $this->phone, $this->email, $hashedPassword, $this->role);

        // Return a success message if the insertion was successful, otherwise an error message
        if ($stmt->execute()) {
            return "User created successfully!"; // Return success message instead of redirecting
        } else {
            return "Error: " . $stmt->error;
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
            $errors[] = "Antall personer må være minst 1.";
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
