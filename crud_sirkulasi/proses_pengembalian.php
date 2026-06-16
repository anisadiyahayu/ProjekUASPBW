<?php

include "koneksi.php";

$id = $_GET['id'];

mysqli_query($conn, "
    UPDATE peminjaman
    SET status='dikembalikan'
    WHERE id='$id'
");

header("Location: pengembalian.php");
exit;
