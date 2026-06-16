<?php
include "auth.php";
include "koneksi.php";

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $nama_lokasi = mysqli_real_escape_string($conn, $_POST['nama_lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    mysqli_query($conn, "UPDATE locations SET nama_lokasi='$nama_lokasi', keterangan='$keterangan' WHERE id='$id'");
}

header("Location: lokasi.php");
exit;