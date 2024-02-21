// editRecord.js
function editRecord(id) {
    // Redirect to form.php with the patient ID as a query parameter
    window.location.href = '/staff/form.php?id=' + id;
}

// Function to update patient record (can be placed in a separate script file)
function updateRecord() {
    // Get data from the edit form
    var id = document.getElementById('editId').value;
    var name = document.getElementById('editName').value;
    var age = document.getElementById('editAge').value;
    var gender = document.getElementById('editGender').value;

    // Add more fields as needed

    // Send AJAX request to update the record
    $.ajax({
        type: 'POST',
        url: 'update_record.php', // Replace with your PHP script for updating records
        data: {
            id: id,
            name: name,
            age: age,
            gender: gender
            // Add more fields as needed
        },
        success: function (response) {
            // Handle the response from the server (e.g., show a success message)
            alert('Record updated successfully!');
            // Redirect back to the dashboard or any other desired page
            window.location.href = '/dashboard.php';
        },
        error: function () {
            // Handle errors (e.g., show an error message)
            alert('Error updating record. Please try again.');
        }
    });
}

// Function to open the edit modal
function openEditModal(id) {
    // Get patient details by ID (you need to implement this function)
    var patientDetails = getPatientDetailsById(id);

    // Update the form fields with patient details
    document.getElementById('editId').value = id;
    document.getElementById('editName').value = patientDetails.name;
    document.getElementById('editAge').value = patientDetails.age;
    document.getElementById('editGender').value = patientDetails.gender;

    // Add similar lines for other fields

    // Open the edit modal
    $('#editModal').modal('show');
}

// Implement the logic to fetch patient details by ID from your database using AJAX
// Fetch patient details by ID
function getPatientDetailsById(id) {
    // Perform an AJAX request to get patient details from the server
    $.ajax({
        type: 'GET',
        url:'your_update_endpoint.php', // Replace with your actual server endpoint
        data: { id: id },
        success: function (response) {
            // Populate the form fields with the fetched data
            var patientDetails = JSON.parse(response);
            document.getElementById('name').value = patientDetails.name;
            document.getElementById('age').value = patientDetails.age;
            document.getElementById('gender').value = patientDetails.gender;
            document.getElementById('contactNo').value = patientDetails.contact_no;
            document.getElementById('address').value = patientDetails.address;
            document.getElementById('dropdown').value = patientDetails.selected_test;
            document.getElementById('consultingDoctor').value = patientDetails.consulting_doctor;
            document.getElementById('dateTime').value = patientDetails.date_time;
        },
        error: function () {
            // Handle errors
            alert('Error fetching patient details. Please try again.');
        }
    });
}

// Get the ID from the hidden input
var patientId = document.getElementById('id').value;

// Check if the ID is present (indicating an edit operation)
if (patientId !== '') {
    // Call the function to fetch and populate patient details
    getPatientDetailsById(patientId);
}

function billRecord(id) {
    // Redirect to your billing page or handle the billing logic
    window.location.href = 'invoice.php?id=' + id;
}
