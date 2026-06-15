<?php

session_start();

if($_SESSION['role'] != 'admin'){
    die("Akses ditolak");
}

include '../koneksi.php';

$id = $_GET['id'];

mysqli_query(
$conn,
"DELETE FROM items
WHERE id='$id'"
);

header("Location:data_barang.php");