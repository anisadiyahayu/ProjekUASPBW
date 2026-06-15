<?php

session_start();

if($_SESSION['role'] != 'admin'){
    die("Akses ditolak");
}

include '../koneksi.php';

$cek = mysqli_query(
$conn,
"SELECT * FROM items
WHERE kode='$_POST[kode]'"
);

if(mysqli_num_rows($cek)>0){
    die("Kode barang sudah digunakan");
}

$foto = $_FILES['foto']['name'];

move_uploaded_file(
$_FILES['foto']['tmp_name'],
"../assets/uploads/".$foto
);

mysqli_query($conn,"
    INSERT INTO items (
        id, kode, nama, id_kategori, id_lokasi, stok, satuan, kondisi, foto_barang, stok_minimum, keterangan
    ) VALUES (
        UUID(),
        '$_POST[kode]',
        '$_POST[nama]',
        '$_POST[id_kategori]',
        '$_POST[id_lokasi]',
        '$_POST[stok]',
        '$_POST[satuan]',
        '$_POST[kondisi]',
        '$foto',
        '$_POST[stok_minimum]',
        '$_POST[keterangan]'
    )
");
?>