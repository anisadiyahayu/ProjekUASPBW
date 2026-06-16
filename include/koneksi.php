<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_inventaris_lab";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function formatWaktu($datetime) {
    if (!$datetime) return "-";
    return date('d M Y, H:i', strtotime($datetime));
}
?>