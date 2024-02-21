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
