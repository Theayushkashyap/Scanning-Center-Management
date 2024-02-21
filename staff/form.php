<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';

// Fetch study descriptions and prices from scanning_types table
$studyQuery = "SELECT study_desc_name, price FROM scanning_types";
$studyResult = mysqli_query($conn, $studyQuery);
$studyData = mysqli_fetch_all($studyResult, MYSQLI_ASSOC);

$studyOptions = "";
foreach ($studyData as $row) {
    $studyOptions .= '<option value="' . $row['study_desc_name'] . '" data-price="' . $row['price'] . '">' . $row['study_desc_name'] . '</option>';
}

// Initialize payable amount
$payableAmount = 600.00;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedStudy = $_POST['dropdown'];
    $selectedStudyPrice = 0.00;

    // Find the selected study's price from the studyData array
    foreach ($studyData as $study) {
        if ($study['study_desc_name'] == $selectedStudy) {
            $selectedStudyPrice = $study['price'];
            break;
        }
    }

    // Calculate payable amount based on selected study's price
    $payableAmount = $selectedStudyPrice;

    // Define variables
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact_no = $_POST['contactNo'];
    $selected_test = $_POST['dropdown'];
    $consulting_doctor = $_POST['consultingDoctor'];
    $address = $_POST['address'];
    $record_date = $_POST['recordDate'];
    $record_time = $_POST['recordTime'];
    $id = $_POST['id'];

    // Check if an ID is provided. If yes, update the record; otherwise, insert a new record
    if (!empty($id)) {
        // Update the existing record using prepared statement
        $update_query = "UPDATE patient_records SET name=?, age=?, gender=?, contact_no=?, study_desc=?, consulting_doctor=?, address=?, record_date=?, record_time=?, amount=?
            WHERE id=?";

        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssssssssis", $name, $age, $gender, $contact_no, $selected_test, $consulting_doctor, $address, $record_date, $record_time, $payableAmount, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "Record updated successfully!";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        // Insert a new record using prepared statement
        $insert_query = "INSERT INTO patient_records (name, age, gender, contact_no, study_desc, consulting_doctor, address, record_date, record_time, amount)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, "sssssssssi", $name, $age, $gender, $contact_no, $selected_test, $consulting_doctor, $address, $record_date, $record_time, $payableAmount);

        if (mysqli_stmt_execute($stmt)) {
            echo "Record inserted successfully!";
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reg Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .form-group label {
            flex: 1;
            margin-right: 10px;
            text-align: right;
        }

        .form-group input,
        .form-group select {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .close-button {
            background-color: #f44336;
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: absolute;
            bottom: 0px;
            right: 10px;
        }
    </style>
</head>

<body>

<div class="container">
        <h2>Patient Reg Form</h2>
        <form action="#" method="post">
            <!-- Include the ID field in the form -->
            <input type="hidden" id="id" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
    <label for="contactNo">Contact No:</label>
    <input type="number" id="contactNo" name="contactNo" title="Please enter a 10-digit number">
</div>


<div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
        <label for="dropdown">Study description:</label>
        <select id="dropdown" name="dropdown" required onchange="updatePayableAmount()">
            <?php echo $studyOptions; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="payableAmount">Payable Amount:</label>
        <input type="text" id="payableAmount" name="payableAmount" value="<?php echo $payableAmount; ?>" readonly>
    </div>
            <div class="form-group">
                <label for="consultingDoctor">Consulting Doctor:</label>
                <input type="text" id="consultingDoctor" name="consultingDoctor" required>
            </div>
            <div class="form-group">
                <label for="recordDate">Record Date:</label>
                <input type="date" id="recordDate" name="recordDate" required>
            </div>
            <div class="form-group">
                <label for="recordTime">Record Time:</label>
                <input type="time" id="recordTime" name="recordTime" required>
            </div>
            <button class="submit-button" onclick="document.querySelector('form').submit();">Submit</button>
            <button class="close-button" onclick="location.href='/staff/staff.php';">X</button>
        </form>
    </div>

    <script>
        // Function to format the date and time in IST
        function formatISTDate(date) {
            var offset = 330; // Offset for IST (in minutes)
            var istDate = new Date(date.getTime() + offset * 60000);
            return istDate.toISOString().slice(0, 16); // Format as 'YYYY-MM-DDTHH:mm'
        }

        // Function to update the date and time field
        function updateDateTime() {
            var now = new Date();
            var dateTimeField = document.getElementById('dateTime');
            dateTimeField.value = formatISTDate(now);
        }

        // Update the date and time every second
        setInterval(updateDateTime, 1000);

        // Initial call to set the initial value
        updateDateTime();
    </script>
    <script>
        function updatePayableAmount() {
            var dropdown = document.getElementById('dropdown');
            var payableAmountField = document.getElementById('payableAmount');
            var selectedOption = dropdown.options[dropdown.selectedIndex];
            var selectedPrice = selectedOption.getAttribute('data-price');
            payableAmountField.value = selectedPrice;
        }
    </script>

</body>

</html>
