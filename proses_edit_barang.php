<?php

session_start();

if($_SESSION['role'] != 'admin'){
    die("Akses ditolak");
}

include '../koneksi.php';

$id = $_GET['id'];

mysqli_query($conn,"
    UPDATE items SET
    kode='$_POST[kode]',
    nama='$_POST[nama]',
    id_kategori='$_POST[id_kategori]',
    id_lokasi='$_POST[id_lokasi]',
    stok='$_POST[stok]',
    satuan='$_POST[satuan]',
    kondisi='$_POST[kondisi]',
    stok_minimum='$_POST[stok_minimum]',
    keterangan='$_POST[keterangan]'
    WHERE id='$id'
");