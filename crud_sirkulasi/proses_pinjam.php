<?php

include "auth.php";
include "koneksi.php";

$user_id = $_SESSION['id'];

$item_id = $_POST['item_id'];
$jumlah = $_POST['jumlah'];
$keperluan = $_POST['keperluan'];
$tanggal_pinjam = $_POST['tanggal_pinjam'];
$tanggal_kembali = $_POST['tanggal_kembali'];

mysqli_query($conn,"
INSERT INTO peminjaman
(
user_id,
item_id,
jumlah,
keperluan,
tanggal_pinjam,
tanggal_kembali,
status
)
VALUES
(
'$user_id',
'$item_id',
'$jumlah',
'$keperluan',
'$tanggal_pinjam',
'$tanggal_kembali',
'pending'
)
");

header("Location:peminjaman.php");
exit;