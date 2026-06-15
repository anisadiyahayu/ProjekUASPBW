<?php

include("../koneksi.php");

$id = $_GET['id'];

mysqli_query(
    $koneksi,
    "DELETE FROM categories
    WHERE id='$id'"
);

header("Location:index.php");