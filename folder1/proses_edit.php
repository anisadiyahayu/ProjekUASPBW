<?php
// proses_edit.php
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Keamanan: Mencegah SQL Injection dengan real_escape_string
    $id          = $conn->real_escape_string($_POST['id']);
    $nama_lokasi = $conn->real_escape_string($_POST['nama_lokasi']);
    $deskripsi   = $conn->real_escape_string($_POST['deskripsi']);

    // Query Update Data
    $query = "UPDATE lokasi_barang SET nama_lokasi = '$nama_lokasi', deskripsi = '$deskripsi' WHERE id = '$id'";

    if ($conn->query($query) === TRUE) {
        // Redirect ke index jika sukses
        header("Location: index.php?pesan=edit_sukses");
        exit();
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
} else {
    // Redirect jika file ini diakses langsung
    header("Location: index.php");
    exit();
}
?>