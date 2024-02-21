// deleteRecord.js
var recordIdToDelete; // Variable to store the ID of the record to be deleted

// Function to open the delete confirmation modal
function openDeleteConfirmationModal(id) {
    recordIdToDelete = id;
    $('#deleteConfirmationModal').modal('show');
}

// Function to handle the delete operation after confirmation
function deleteRecordConfirmed() {
    // Find and remove the row from the table using JavaScript
    var table = document.getElementById("dataTable");
    var rowIndex = Array.from(table.rows).findIndex(row => row.cells[0].textContent === recordIdToDelete.toString());

    if (rowIndex !== -1) {
        table.deleteRow(rowIndex);
        $('#deleteConfirmationModal').modal('hide');
        alert('Record deleted from the table.');
    } else {
        alert('Record not found in the table.');
    }
}
