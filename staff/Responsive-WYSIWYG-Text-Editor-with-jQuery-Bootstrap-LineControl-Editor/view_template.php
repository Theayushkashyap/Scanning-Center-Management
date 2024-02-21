<?php
include 'config.php';

$query = "SELECT * FROM templates";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h2>" . $row['template_name'] . "</h2>";
        echo "<p>" . $row['template_content'] . "</p>";
        echo "<hr>";
    }
} else {
    echo "No templates found.";
}

$conn->close();
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>View Templates</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>View Templates</h2>
        
        <?php
        include 'show_templates.php'; // Include the corrected PHP script filename
        ?>
    </div>
</body>
</html>
