<?php
session_start();
$host = "localhost";
$user = "root";       
$pass = "";           
$db   = "db_inventaris_lab"; 

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Fungsi bantu format waktu
function formatWaktu($datetime) {
    if (!$datetime) return "-";
    return date('d M Y, H:i', strtotime($datetime));
}
?>