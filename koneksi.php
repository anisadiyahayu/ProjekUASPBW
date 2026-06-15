<?php
$conn = new mysqli("localhost", "root", '', "db_invertaris_lab");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>