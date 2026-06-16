<?php
<<<<<<< HEAD
require_once '../auth/auth_check.php';
include "../include/koneksi.php";
=======

include "../include/koneksi.php";

>>>>>>> main_inventaris
$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM categories
    WHERE id='$id'"
);

header("Location:index.php");