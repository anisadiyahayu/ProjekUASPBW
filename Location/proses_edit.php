<?php
// proses_edit.php
require_once 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Keamanan: Mencegah SQL Injection dengan real_escape_string
    $id         = $conn->real_escape_string($_POST['id']);
    $nama       = $conn->real_escape_string($_POST['nama']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Query Update Data
    $query = "UPDATE locations SET nama = '$nama', keterangan = '$keterangan' WHERE id = '$id'";

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