<?php
include 'config.php';
// Include 'config.php' and other necessary code
include 'config.php';

// Get the updated data from the POST request
$id = $_POST['id'];
$name = $_POST['name'];
// Add other fields as needed

// Update the record in the database
$query = "UPDATE patient_records SET name = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "si", $name, $id);

$response = [];

if (mysqli_stmt_execute($stmt)) {
    // Update successful
    $response['status'] = 'success';
    $response['message'] = 'Record updated successfully';
} else {
    // Update failed
    $response['status'] = 'error';
    $response['message'] = 'Error updating record: ' . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
