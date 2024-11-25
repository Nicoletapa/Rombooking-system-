<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../../vendor/autoload.php';
include($_SERVER['DOCUMENT_ROOT'] . '/Rombooking-system-/Includes/config.php');

if (isset($_GET['reservation_id'])) {
    $reservationID = $_GET['reservation_id'];

    // Fetch reservation details from the database
    $sql = "
        SELECT r.ReservasjonID, r.BrukerID, r.RomID, r.Innsjekk, r.Utsjekk, rt.RomTypeNavn, rt.Beskrivelse
        FROM Reservasjon r
        JOIN RomID_RomType rid ON r.RomID = rid.RomID
        JOIN Romtype rt ON rid.RomtypeID = rt.RomtypeID
        WHERE r.ReservasjonID = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationID);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    if ($reservation) {
        // Create a new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Add reservation details to the PDF
        $pdf->Cell(0, 10, 'Reservation Details', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Reservation ID: ' . $reservation['ReservasjonID'], 0, 1);
        $pdf->Cell(0, 10, 'User ID: ' . $reservation['BrukerID'], 0, 1);
        $pdf->Cell(0, 10, 'Room ID: ' . $reservation['RomID'], 0, 1);
        $pdf->Cell(0, 10, 'Room Type: ' . $reservation['RomTypeNavn'], 0, 1);
        $pdf->Cell(0, 10, 'Description: ' . $reservation['Beskrivelse'], 0, 1);
        $pdf->Cell(0, 10, 'Check-in: ' . $reservation['Innsjekk'], 0, 1);
        $pdf->Cell(0, 10, 'Check-out: ' . $reservation['Utsjekk'], 0, 1);

        // Output the PDF
        $pdf->Output('D', 'Reservation_' . $reservationID . '.pdf');
    } else {
        echo "Reservation not found.";
    }
} else {
    echo "No reservation ID provided.";
}
?>