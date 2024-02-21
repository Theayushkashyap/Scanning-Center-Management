<?php
include 'config.php';  // Include the database connection code

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Get form data
    $templateName = $_POST['template_name'];
    $existingTemplate = $_FILES['existing_template']['name'];
    $templateContent = $_POST['template_content'];

    // Upload existing template file (if provided)
    if (!empty($_FILES['existing_template']['tmp_name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['existing_template']['name']);
        move_uploaded_file($_FILES['existing_template']['tmp_name'], $target_file);
    } else {
        $existingTemplate = null;
    }

    // Prepare and execute the SQL query using prepared statements
    $sql = "INSERT INTO templates (template_name, existing_template, template_content) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $templateName, $existingTemplate, $templateContent);

    if ($stmt->execute()) {
        echo "Template data stored successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and the database connection
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="editor.js"></script>
    <script>
        $(document).ready(function() {
            $("#txtEditor").Editor();
        });
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="editor.css" type="text/css" rel="stylesheet"/>
    <title>LineControl | v1.1.0</title>
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <h2 class="demo-text">Create New Document Template</h2>
            <div class="container">
                <form action="process_template.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="template_name">Template Name:</label>
                            <input type="text" class="form-control" id="template_name" name="template_name" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="existing_template">Existing Template:</label>
                            <input type="file" class="form-control" id="existing_template" name="existing_template">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label for="template_content">Template Content:</label>
                            <textarea id="txtEditor" name="template_content"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Save Template</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid footer">
        <p class="pull-right">&copy; Your Company <script>document.write(new Date().getFullYear())</script>. All rights reserved.</p>
    </div>
</body>
</html>
