<?php

<<<<<<< HEAD
require_once '../auth/auth_check.php';
=======
>>>>>>> main_inventaris
include "../include/koneksi.php";

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];

$sql = "INSERT INTO categories
        (nama,deskripsi)
        VALUES
        ('$nama','$deskripsi')";

if(mysqli_query($conn,$sql)){
    header("Location:index.php");
    exit;
}else{
    echo mysqli_error($conn);
}