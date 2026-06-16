<?php
<<<<<<< HEAD
require_once '../auth/auth_check.php';
=======

>>>>>>> main_inventaris
include "../include/koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

mysqli_query(
    $conn,
    "UPDATE categories
     SET
        nama='$nama',
        deskripsi='$deskripsi'
     WHERE id='$id'"
);

header("Location: index.php");
exit;