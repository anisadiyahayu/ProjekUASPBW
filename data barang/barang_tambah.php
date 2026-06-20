<?php

include "auth.php";
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $id_lokasi   = $_POST['id_lokasi'];
    $id_lab      = $_POST['id_lab'];
    $stok        = $_POST['stok'];
    $kondisi     = $_POST['kondisi'];
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(
            pathinfo(
                $_FILES['gambar']['name'],
                PATHINFO_EXTENSION
            )
        );
        $gambar = time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            'uploads/' . $gambar
        );
    }

    $query = mysqli_query($conn, "
        INSERT INTO items
        (
            kode_barang,
            nama_barang,
            id_kategori,
            id_lokasi,
            id_lab,
            stok,
            kondisi,
            gambar,
            deskripsi,
            created_at
        )
        VALUES
        (
            '$kode_barang',
            '$nama_barang',
            '$id_kategori',
            '$id_lokasi',
            '$id_lab',
            '$stok',
            '$kondisi',
            '$gambar',
            '$deskripsi',
            NOW()
        )
    ");

    if ($query) {
        echo "
        <script>
        alert('Barang berhasil ditambahkan');
        location='barang.php';
        </script>
        ";
        exit;
    } else {
        die("Gagal menyimpan ke database: " . mysqli_error($conn));
    }
} else {
    header("Location: barang.php");
    exit;
}