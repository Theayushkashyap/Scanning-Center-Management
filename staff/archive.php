<?php
include 'config.php';

date_default_timezone_set('Asia/Kolkata');

function fetchRecordsByDate($conn, $date) {
    $query = "SELECT * FROM patient_records WHERE DATE(record_date) = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : array();
}

function fetchRecordsByMonth($conn, $selectedMonth, $selectedYear) {
    // Construct the date string in 'YYYY-MM' format
    $dateParam = $selectedYear . '-' . $selectedMonth;
    $query = "SELECT * FROM patient_records WHERE DATE_FORMAT(record_date, '%Y-%m') = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $dateParam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : array();
}

$selectedMonth = $_GET['month'] ?? date('m');
$selectedYear = $_GET['year'] ?? date('Y');

// Initialize $todayRows
$todayRows = array();

// Fetch records based on the selected month and year
if (checkRecordsExist($conn, $selectedMonth, $selectedYear)) {
    $todayRows = fetchRecordsByMonth($conn, $selectedMonth, $selectedYear);
}

mysqli_close($conn);

function checkRecordsExist($conn, $selectedMonth, $selectedYear) {
    $dateParam = $selectedYear . '-' . $selectedMonth;
    $query = "SELECT COUNT(*) as count FROM patient_records WHERE DATE_FORMAT(record_date, '%Y-%m') = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $dateParam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    return $row['count'] > 0;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Archive</title>

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
            <li class="nav-item">
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
            <li class="nav-item active">
                <a class="nav-link " href="archive.php">
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
    <h1 class="h3 mb-0 text-gray-800">Patient Data - <?php echo date('F Y', strtotime('01-' . $selectedMonth . '-' . $selectedYear)); ?>
</h1>

        <div class="dropdown">
            <label for="month">Select Month:</label>
            <select id="month" name="month" onchange="changeMonth(this)">
            <?php
           for ($i = 1; $i <= 12; $i++) {
            $monthValue = sprintf("%02d", $i);
            $selected = ($monthValue == $selectedMonth) ? 'selected' : '';
            echo '<option value="' . $monthValue . '&year=' . $selectedYear . '" ' . $selected . '>' . date('F', mktime(0, 0, 0, $i, 1)) . '</option>';
        }
        
            ?>
            </select>
            <label for="year">Select Year:</label>
<select id="year" name="year" onchange="changeYear(this)">
    <?php
    $currentYear = date('Y');
    for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
        $selected = ($i == $selectedYear) ? 'selected' : '';
        echo '<option value="' . $selectedMonth . '&year=' . $i . '" ' . $selected . '>' . $i . '</option>';
    }
    
    ?>
</select>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Patient Records - <?php echo date('F Y', strtotime('01-' . $selectedMonth . '-' . $selectedYear)); ?>
</h6>
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
           
   <!-- Add these lines to include scripts in your HTML file -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="js/sb-admin-2.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="js/demo/datatables-demo.js"></script>
<script src="./js/archive/deleteRecord.js"></script>
<script src="./js/archive/editRecord.js"></script>
<script src="./js/archive/main.js"></script>
<script>
    function changeMonth(select) {
        var selectedMonth = select.value;
        window.location.href = 'archive.php?month=' + selectedMonth;
    }
</script>
<script>
    function changeYear(select) {
        var selectedYear = select.value;
        var selectedMonth = document.getElementById('month').value;
        window.location.href = 'archive.php?month=' + selectedMonth + '&year=' + selectedYear;
    }
</script>

</body>
</html>