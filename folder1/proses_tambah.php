<?php
// proses_tambah.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape string untuk menghindari SQL Injection
    $nama = $conn->real_escape_string($_POST['nama']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Query insert menggunakan UUID() mysql untuk kolom id (varchar 50)
    $query = "INSERT INTO locations (id, nama, keterangan) VALUES (UUID(), '$nama', '$keterangan')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data lokasi berhasil ditambahkan!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}
$conn->close();
?>