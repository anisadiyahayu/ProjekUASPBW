<?php

include "auth.php";
include "koneksi.php";

if(isset($_POST['id']))
{

    $id = $_POST['id'];

    $nama = mysqli_real_escape_string(
        $conn,
        $_POST['nama']
    );

    $deskripsi = mysqli_real_escape_string(
        $conn,
        $_POST['deskripsi']
    );

    mysqli_query(
        $conn,
        "UPDATE categories
        SET
        nama='$nama',
        deskripsi='$deskripsi'
        WHERE id='$id'"
    );

}

header("Location:kategori.php");
exit;