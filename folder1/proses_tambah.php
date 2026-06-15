<?php
// proses_tambah.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mencegah SQL Injection
    $nama = $conn->real_escape_string($_POST['nama']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Generate UUID untuk primary key tipe varchar(50) secara otomatis dari MySQL
    $query = "INSERT INTO locations (id, nama, keterangan) VALUES (UUID(), '$nama', '$keterangan')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Lokasi berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>