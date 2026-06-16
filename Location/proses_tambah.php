<?php
// proses_tambah.php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Membuat UUID v4 secara acak untuk kolom ID (karena tabel menggunakan tipe varchar, bukan auto-increment)
    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    // Keamanan: Mencegah SQL Injection dengan real_escape_string
    $nama       = $conn->real_escape_string($_POST['nama']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Query Insert Data
    $query = "INSERT INTO locations (id, nama, keterangan) VALUES ('$id', '$nama', '$keterangan')";

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