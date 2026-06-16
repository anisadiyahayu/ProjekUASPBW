<?php
$conn = mysqli_connect("localhost", "root", "root", "db_inventaris_lab");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>