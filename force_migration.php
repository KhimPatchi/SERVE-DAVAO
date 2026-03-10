<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "servedavao";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Make message column nullable
$sql = "ALTER TABLE messages MODIFY COLUMN message TEXT NULL";
if ($conn->query($sql) === TRUE) {
    echo "Column 'message' is now nullable.\n";
} else {
    echo "Error modifying 'message' column: " . $conn->error . "\n";
}

$conn->close();
?>
