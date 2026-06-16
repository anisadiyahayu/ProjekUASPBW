<?php

include "auth.php";
include "koneksi.php";

if(isset($_POST['nama']))
{

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
        "INSERT INTO categories
        (
            nama,
            deskripsi,
            created_at
        )
        VALUES
        (
            '$nama',
            '$deskripsi',
            NOW()
        )"
    );

}

header("Location:kategori.php");
exit;