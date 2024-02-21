<?php
include 'config.php';

$patientId = $_GET['id'] ?? null;

if (!$patientId) {
    echo "Invalid patient ID";
    exit;
}

$query = "SELECT * FROM patient_records WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $patientId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$patientDetails = $result ? mysqli_fetch_assoc($result) : null;

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .invoice-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1, h2, p {
            color: #333;
        }

        h1 {
            text-align: center;
        }

        h2 {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .patient-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .total-amount {
            margin-top: 20px;
            text-align: right;
        }

        #options {
            text-align: center;
            margin-top: 20px;
        }

        #options button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        #options button:last-child {
            margin-right: 0;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <h1>Invoice</h1>
        <div class="patient-details">
            <h2>Patient Details</h2>
            <p>Patient ID: <?php echo $patientDetails['id']; ?></p>
            <p>Name: <?php echo $patientDetails['name']; ?></p>
            <p>Age: <?php echo $patientDetails['age']; ?></p>
            <p>Gender: <?php echo $patientDetails['gender']; ?></p>
            <p>Contact No: <?php echo $patientDetails['contact_no']; ?></p>
            <p>Registration Date: <?php echo $patientDetails['record_date'] . ' ' . $patientDetails['record_time']; ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Add rows for each item in the invoice -->
                <tr>
                    <td><p><?php echo $patientDetails['study_desc']; ?></p></td>
                    <td>₹<?php echo $patientDetails['amount']; ?></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>

        <div class="total-amount">
            <h2>Total Amount: ₹<?php echo $patientDetails['amount']; ?></h2>
        </div>

        <div id="options" style="display: block; text-align: center; margin-top: 20px;">
        <button onclick="printInvoice()">Print</button>
        <button onclick="downloadPDF()">Download PDF</button>
        <button onclick="redirectToDashboard()">Close</button>
    </div>

    <script>
        function printInvoice() {
            document.getElementById('options').style.display = 'none';  // Hide the options
            window.print();  // Trigger print
        }
        function redirectToDashboard() {
            window.location.href = 'staff.php';  // Adjust the URL as needed
        }
        function downloadPDF() {
            document.getElementById('options').style.display = 'none';  // Hide the options

            // Using dompdf library to convert HTML to PDF
            var htmlContent = document.querySelector('.invoice-container').outerHTML;
            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write('<html><head><title>PDF</title></head><body>');
            printWindow.document.write(htmlContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Delayed button reappearing after PDF download is complete
            setTimeout(function() {
                document.getElementById('options').style.display = 'block';
            }, 1000);  // Adjust the delay as needed
        }

        // Event listener for afterprint event (fired after printing)
        window.addEventListener("afterprint", function() {
            // Reveal the options after printing is complete
            document.getElementById('options').style.display = 'block';
        });
    </script>
    
</body>

</html>
