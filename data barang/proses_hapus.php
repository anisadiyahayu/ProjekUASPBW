<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("s", $id);

    if ($stmt->execute()) {
        header("Location: index.php?msg=hapus_sukses");
    } else {
        echo "<script>alert('Gagal menghapus data: Barang ini mungkin sedang terikat pada transaksi peminjaman.'); window.history.back();</script>";
    }
    $stmt->close();
} else {
    header("Location: index.php");
}
?>