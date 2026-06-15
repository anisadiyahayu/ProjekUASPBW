<?php

$koneksi = mysqli_connect(
    "localhost",
    "root",
    "",
    "db_inventaris_lab"
);

if (!$koneksi) {
    die("Koneksi gagal : " . mysqli_connect_error());
}

?>