<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus! User mungkin terikat transaksi.'); window.location='index.php';</script>";
    }
    $stmt->close();
} else {
    echo "<script>window.location='index.php';</script>";
}
exit;
?>