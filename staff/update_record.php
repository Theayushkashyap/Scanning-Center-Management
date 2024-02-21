<!-- update_record.php -->
<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle form submission to update the record in the database
    $id = $_POST['id'];
    $newData = $_POST['new_data'];

    $updateQuery = "UPDATE patient_records SET column_name = ? WHERE id = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "si", $newData, $id);
    mysqli_stmt_execute($updateStmt);

    // Redirect back to the archive page
    header("Location: /staff/archive.php");
    exit();
} else {
    // Fetch the record details based on the provided ID
    $recordId = $_GET['id'];
    $fetchQuery = "SELECT * FROM patient_records WHERE id = ?";
    $fetchStmt = mysqli_prepare($conn, $fetchQuery);
    mysqli_stmt_bind_param($fetchStmt, "i", $recordId);
    mysqli_stmt_execute($fetchStmt);
    $result = mysqli_stmt_get_result($fetchStmt);
    $recordDetails = mysqli_fetch_assoc($result);

    // Display the form to update the record
    // (Note: You need to customize this part based on your actual table structure)
    echo "<form method='POST' action='update_record.php'>";
    echo "<input type='hidden' name='id' value='{$recordDetails['id']}'>";
    echo "New Data: <input type='text' name='new_data' value='{$recordDetails['column_name']}'>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}
?>
