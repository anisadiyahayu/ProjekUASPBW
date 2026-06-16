<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Menangkap data dari Modal Edit index.php
    $id          = $_POST['id'];
    $kode        = $_POST['kode'];
    $nama        = $_POST['nama'];
    $id_kategori = !empty($_POST['id_kategori']) ? $_POST['id_kategori'] : NULL;
    $id_lokasi   = !empty($_POST['id_lokasi']) ? $_POST['id_lokasi'] : NULL;
    $stok        = $_POST['stok'];
    $satuan      = $_POST['satuan'];
    $kondisi     = $_POST['kondisi'];
    $keterangan  = $_POST['keterangan'];

    // Update ke database
    $stmt = $conn->prepare("UPDATE items SET kode=?, nama=?, id_kategori=?, id_lokasi=?, stok=?, satuan=?, kondisi=?, keterangan=? WHERE id=?");
    $stmt->bind_param("ssssissss", $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $keterangan, $id);

    if ($stmt->execute()) {
        header("Location: index.php?msg=edit_sukses");
    } else {
        echo "<script>alert('Gagal mengedit barang! Error: " . $stmt->error . "'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>