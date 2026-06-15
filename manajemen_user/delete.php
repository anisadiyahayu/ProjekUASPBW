<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

// Pastikan ID tidak kosong
if ($id) {
    // Kembalikan ke "s" karena ID kalian ternyata bertipe teks/string (Varchar) seperti 'admin_1'
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("s", $id);
    
    if ($stmt->execute()) {
        // Berhasil dihapus
    } else {
        // Jika gagal karena masalah database relasi (Foreign Key)
        echo "<script>alert('Gagal menghapus! User mungkin terikat transaksi.');</script>";
    }
    $stmt->close();
}

// Kembalikan ke halaman utama tabel user
header("Location: index.php");
exit;
?>