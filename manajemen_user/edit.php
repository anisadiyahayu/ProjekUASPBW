<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

$id = $_GET['id'] ?? '';

// Validasi jika user tidak ditemukan
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<script>window.location='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $no_hp = $_POST['no_hp'];
    $status_akun = $_POST['status_akun'] ?? $user['status_akun'];

    $stmtUpdate = $conn->prepare("UPDATE users SET nama=?, role=?, kelas=?, angkatan=?, no_hp=?, status_akun=? WHERE id=?");
    $stmtUpdate->bind_param("sssssss", $nama, $role, $kelas, $angkatan, $no_hp, $status_akun, $id);
    
    if($stmtUpdate->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>