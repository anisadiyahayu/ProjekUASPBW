<?php
include "auth.php";
include "koneksi.php";

if (isset($_POST['nama_lokasi'])) {
    $nama_lokasi = mysqli_real_escape_string($conn, $_POST['nama_lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    mysqli_query($conn, "INSERT INTO locations (nama_lokasi, keterangan) VALUES ('$nama_lokasi', '$keterangan')");
}

header("Location: lokasi.php");
exit;