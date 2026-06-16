<?php
<<<<<<< HEAD
require_once '../auth/auth_check.php';
=======

>>>>>>> main_inventaris
include "../include/koneksi.php";

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

$id = uniqid('CAT');

mysqli_query(
    $conn,
    "INSERT INTO categories(id,nama,deskripsi)
     VALUES('$id','$nama','$deskripsi')"
);

header("Location: index.php");
exit;