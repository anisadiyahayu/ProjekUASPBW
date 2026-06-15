<?php
// hapus.php
require_once 'koneksi.php';

// Cek apakah parameter ID tersedia di URL
if (isset($_GET['id'])) {
    // Keamanan: Mencegah SQL Injection
    $id = $conn->real_escape_string($_GET['id']);

    // Query Delete Data
    $query = "DELETE FROM lokasi_barang WHERE id = '$id'";

    if ($conn->query($query) === TRUE) {
        // Redirect ke index jika sukses
        header("Location: index.php?pesan=hapus_sukses");
        exit();
    } else {
        echo "Error menghapus data: " . $conn->error;
    }
} else {
    // Redirect jika tidak ada id yang diberikan
    header("Location: index.php");
    exit();
}
?>