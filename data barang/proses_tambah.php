<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );

    $kode        = trim($_POST['kode']);
    $nama        = trim($_POST['nama']);
    
    // Fallback 'NONE' jika data tidak tertangkap form
    $id_kategori = isset($_POST['id_kategori']) ? $_POST['id_kategori'] : 'NONE';
    $id_lokasi   = isset($_POST['id_lokasi']) ? $_POST['id_lokasi'] : 'NONE';
    
    // Cegah masuk jika yang dikirim benar-benar placeholder 'NONE'
    if ($id_kategori === 'NONE' || $id_lokasi === 'NONE') {
        echo "<script>alert('GAGAL: Kategori dan Lokasi WAJIB dipilih!'); window.history.back();</script>";
        exit;
    }
    
    $stok        = (int)$_POST['stok'];
    $satuan      = trim($_POST['satuan']);
    $kondisi     = trim($_POST['kondisi']);
    $keterangan  = trim($_POST['keterangan']);

    $stmt = $conn->prepare("INSERT INTO items (id, kode, nama, id_kategori, id_lokasi, stok, satuan, kondisi, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssisss", $id, $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan);

    if ($stmt->execute()) {
        header("Location: index.php?msg=tambah_sukses");
    } else {
        echo "<script>alert('Gagal menambah barang! Pastikan kode barang tidak sama. Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>