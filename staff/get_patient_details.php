<?php
// Include 'config.php' and other necessary code
include 'config.php';

// Get the patient ID from the GET request
$id = $_GET['id'];

// Fetch the patient details from the database
$query = "SELECT * FROM patient_records WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if a record is found
if ($row = mysqli_fetch_assoc($result)) {
    // Return the patient details as JSON
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'data' => $row]);
} else {
    // No record found
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Patient not found']);
}

// Close the database connection
mysqli_close($conn);
?>
