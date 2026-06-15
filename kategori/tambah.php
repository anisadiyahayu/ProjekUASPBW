<?php

include "koneksi.php";

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

mysqli_query(
    $conn,
    "INSERT INTO categories(nama,deskripsi)
     VALUES('$nama','$deskripsi')"
);

header("Location: index.php");
exit;