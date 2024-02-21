<?php
include 'config.php';

date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d");

// Start or resume a session
session_start();

// Assuming you have a session variable storing the user ID and username
$loggedInUserId = $_SESSION['user_id'] ?? null;
$loggedInUsername = $_SESSION['user_name'] ?? null;

// Fetch user details from the database based on the logged-in user ID
if ($loggedInUserId) {
    $userQuery = "SELECT name FROM users WHERE id = ?";
    $userStmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($userStmt, "i", $loggedInUserId);
    mysqli_stmt_execute($userStmt);
    $userResult = mysqli_stmt_get_result($userStmt);
    $userData = mysqli_fetch_assoc($userResult);

    // Store the user name in a session variable for later use
    $_SESSION['user_name'] = $userData['name'];
}
// Calculate the total amount
$totalAmountQuery = "SELECT SUM(amount) AS total_amount FROM patient_records WHERE DATE(record_date) = ?";

$totalAmountStmt = mysqli_prepare($conn, $totalAmountQuery);
mysqli_stmt_bind_param($totalAmountStmt, "s", $today);
mysqli_stmt_execute($totalAmountStmt);
$totalAmountResult = mysqli_stmt_get_result($totalAmountStmt);
$totalAmountRow = mysqli_fetch_assoc($totalAmountResult);
$totalAmount = $totalAmountRow['total_amount'] ?? 0;

$query = "SELECT * FROM patient_records WHERE DATE(record_date) = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$todayRows = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : array();
mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <!-- Custom styles for this template-->
<!--button style-->
<link href="/staff/css/button.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script>// Set the logged-in username in the navbar
document.getElementById('loggedInUsername').innerText = '<?php echo $loggedInUsername; ?>';
</script>
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="staff.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class=""></i>
                </div>
                <div class="sidebar-brand-text mx-3">Vismaya Scanning Center</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="staff.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Dailyreport.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Daily Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="archive.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Archive</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small" id="loggedInUsername"><?php echo $loggedInUsername; ?></span>
<img class="img-profile rounded-circle" src="img/undraw_profile.svg">

            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                    Settings
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
                </nav>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="/staff/form.php"
                            class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-plus fa-sm text-white-50"></i> Add Patient </a>
                    </div>

                    <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
        <?php echo date('F d, Y'); ?>
    </div>
    <div class="h5 mb-0 font-weight-bold text-gray-800">&#x20B9;<?php echo number_format($totalAmount, 2); ?></div>
    <!-- &#x20B9; is the HTML entity code for the Indian Rupee symbol -->
</div>

                    <div class="col-auto">
                        <!-- No icon needed here -->
                    </div>
                </div>
            </div>
        </div>
    </div>


                        
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div
                                                class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Requests</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4>Today's Date: <?php echo date("F j, Y"); ?></h4>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Patient Records</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Study Desc </th>
                                            <th>Consulting Dr</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Age</th>
                                            <th>Study Desc</th>
                                            <th>Consulting Dr</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php
$serialNo = 1;
foreach ($todayRows as $row) {
    echo '<tr>';
    echo '<td>' . $serialNo . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    echo '<td>' . $row['gender'] . '</td>';
    echo '<td>' . $row['age'] . '</td>';
    echo '<td>' . $row['study_desc'] . '</td>';
    echo '<td>' . $row['consulting_doctor'] . '</td>';
    echo '<td class="action-buttons">';
    echo '<button class="edit-button" onclick="editRecord(' . $row['id'] . ')">Edit</button>';
    echo '<button class="view-button" onclick="viewRecord(' . $row['id'] . ')">View</button>';
    echo '<button class="delete-button" onclick="openDeleteConfirmationModal(' . $row['id'] . ')">Delete</button>';
    echo '<button class="bill-button" onclick="billRecord(' . $row['id'] . ')">Bill</button>';
    echo '</td>';
    echo '</tr>';
    $serialNo++;
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>

            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                            <a class="btn btn-primary" href="/login.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add this modal code at the end of your HTML body -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this record?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteRecordConfirmed()">Delete</button>
            </div>
        </div>
    </div>
</div>


            <script src="vendor/jquery/jquery.min.js"></script>
            <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
            <script src="js/sb-admin-2.min.js"></script>
            <script src="vendor/datatables/jquery.dataTables.min.js"></script>
            <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
            <script src="js/demo/datatables-demo.js"></script>
            <script>
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
    function billRecord(id) {
    // Redirect to your billing page or handle the billing logic
    window.location.href = 'invoice.php?id=' + id;
}
</script>


<script>
        // Set the logged-in username in the navbar
        document.getElementById('loggedInUsername').innerText = '<?php echo $loggedInUsername; ?>';
    </script>
    <!-- Add this script at the end of your HTML body -->
<script>
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
</script>

</body>
</html>