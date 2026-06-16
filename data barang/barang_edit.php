<?php

include "auth.php";
include "koneksi.php";

$id          = $_POST['id'];
$kode_barang = $_POST['kode_barang'];
$nama_barang = $_POST['nama_barang'];
$id_kategori = $_POST['id_kategori'];
$id_lokasi   = $_POST['id_lokasi'];
$id_lab      = $_POST['id_lab'];
$stok        = $_POST['stok'];
$kondisi     = $_POST['kondisi'];
$deskripsi   = $_POST['deskripsi'];

mysqli_query($conn, "
UPDATE items
SET
    kode_barang = '$kode_barang',
    nama_barang = '$nama_barang',
    id_kategori = '$id_kategori',
    id_lokasi = '$id_lokasi',
    id_lab = '$id_lab',
    stok = '$stok',
    kondisi = '$kondisi',
    deskripsi = '$deskripsi'
WHERE id = '$id'
");

header("Location: barang.php");
exit;