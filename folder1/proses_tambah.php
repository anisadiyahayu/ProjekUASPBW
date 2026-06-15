<?php
// proses_tambah.php
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Keamanan: Mencegah SQL Injection dengan real_escape_string
    $nama_lokasi = $conn->real_escape_string($_POST['nama_lokasi']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Query Insert Data
    $query = "INSERT INTO lokasi_barang (nama_lokasi, deskripsi) VALUES ('$nama_lokasi', '$deskripsi')";

    if ($conn->query($query) === TRUE) {
        // Redirect ke index jika sukses
        header("Location: index.php?pesan=tambah_sukses");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    // Redirect jika file ini diakses langsung tanpa method POST
    header("Location: index.php");
    exit();
}
?>