<?php

include "koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

mysqli_query($koneksi,"
UPDATE categories
SET
nama='$nama',
deskripsi='$deskripsi'
WHERE id='$id'
");

header("Location:index.php");
exit;