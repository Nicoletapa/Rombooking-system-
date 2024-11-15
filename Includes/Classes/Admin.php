<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/Classes/User.php');

class Admin extends User
{
    public function __construct($conn)
    {
        // Call the parent constructor with the database connection
        parent::__construct($conn);
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
}
