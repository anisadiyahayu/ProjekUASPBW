<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $stmt_foto = $conn->prepare("SELECT foto_barang FROM items WHERE id = ?");
    $stmt_foto->bind_param("s", $id);
    $stmt_foto->execute();
    $result = $stmt_foto->get_result();
    $barang = $result->fetch_assoc();
    $stmt_foto->close();

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        if (!empty($barang['foto_barang']) && file_exists('uploads/' . $barang['foto_barang'])) {
            unlink('uploads/' . $barang['foto_barang']);
        }
        header("Location: index.php?msg=hapus_sukses");
    } else {
        echo "<script>alert('Gagal menghapus data: Barang ini mungkin sedang terikat pada transaksi peminjaman.'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>