<?php
$host = "db.chzcbrucbcnh.ap-southeast-2.rds.amazonaws.com";  // Your database host, often "localhost" or "127.0.0.1"
$username = "admin";  // Your database username
$password = "Vismaya123";  // No password for the 'root' user
$database = "vscdb";  // Your database name

$conn = mysqli_connect('db.chzcbrucbcnh.ap-southeast-2.rds.amazonaws.com', 'admin', 'Vismaya123', 'vscdb', 3306);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
