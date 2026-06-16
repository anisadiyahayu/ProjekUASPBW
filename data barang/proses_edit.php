<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id          = $_POST['id'];
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

    $stmt = $conn->prepare("UPDATE items SET kode=?, nama=?, id_kategori=?, id_lokasi=?, stok=?, satuan=?, kondisi=?, keterangan=? WHERE id=?");
    $stmt->bind_param("ssssissss", $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan, $id);

    if ($stmt->execute()) {
        header("Location: index.php?msg=edit_sukses");
    } else {
        echo "<script>alert('Gagal mengedit barang! Error: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>