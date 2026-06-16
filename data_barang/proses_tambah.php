<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Generate UUID V4 untuk ID Tabel items secara otomatis
    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    // Menangkap data dari Modal Tambah index.php
    $kode        = $_POST['kode'];
    $nama        = $_POST['nama'];
    // Jika dropdown tidak dipilih, paksa menjadi NULL agar constraint DB aman
    $id_kategori = !empty($_POST['id_kategori']) ? $_POST['id_kategori'] : NULL;
    $id_lokasi   = !empty($_POST['id_lokasi']) ? $_POST['id_lokasi'] : NULL;
    
    $stok        = $_POST['stok'];
    $satuan      = $_POST['satuan'];
    $kondisi     = $_POST['kondisi'];
    $keterangan  = $_POST['keterangan'];

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO items (id, kode, nama, id_kategori, id_lokasi, stok, satuan, kondisi, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $id, $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan);

    if ($stmt->execute()) {
        header("Location: index.php?msg=tambah_sukses");
    } else {
        echo "<script>alert('Gagal menambah barang! Error: " . $stmt->error . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>