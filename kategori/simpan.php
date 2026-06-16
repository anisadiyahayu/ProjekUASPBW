<?php

require_once '../auth/auth_check.php';
include "../include/koneksi.php";

$id = $_POST['id'];
$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

mysqli_query(
    $conn,
    "INSERT INTO categories
    (id,nama,deskripsi)
    VALUES
    ('$id','$nama','$deskripsi')"
);

header("Location:index.php");
exit;