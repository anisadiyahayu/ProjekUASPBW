<?php

include "koneksi.php";
include "../include/header.php";

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM categories
    WHERE id='$id'"
);

header("Location:index.php");