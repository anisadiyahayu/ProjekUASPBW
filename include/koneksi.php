<?php
$host = "localhost";
$user = "root";
$pass = "root";
$db   = "db_inventaris_lab";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>