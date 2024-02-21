<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $templateName = $_POST['template_name'];
    $templateContent = $_POST['template_content'];

    $query = "INSERT INTO templates (template_name, template_content) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("ss", $templateName, $templateContent);
        $stmt->execute();
        $stmt->close();

        echo "Template saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request!";
}

$conn->close();
?>
