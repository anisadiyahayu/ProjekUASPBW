<?php
// proses_edit.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengamankan input dari form untuk mencegah SQL Injection
    $id = $conn->real_escape_string($_POST['id']);
    $nama = $conn->real_escape_string($_POST['nama']);
    $keterangan = $conn->real_escape_string($_POST['keterangan']);

    // Query untuk update data
    $query = "UPDATE locations SET nama='$nama', keterangan='$keterangan' WHERE id='$id'";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Data lokasi berhasil diperbarui!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}

$conn->close();
?>