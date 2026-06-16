<?php

include "auth.php";
include "koneksi.php";

$id = $_POST['id'];

$data = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT gambar FROM items WHERE id='$id'"
    )
);

if (
    !empty($data['gambar'])
    && file_exists("uploads/" . $data['gambar'])
) {
    unlink("uploads/" . $data['gambar']);
}

mysqli_query(
    $conn,
    "DELETE FROM items WHERE id='$id'"
);

header("Location: barang.php");
exit;