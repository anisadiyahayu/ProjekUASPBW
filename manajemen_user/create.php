<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = uniqid('user_');
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $kelas = $_POST['kelas'];
    $angkatan = $_POST['angkatan'];
    $no_hp = $_POST['no_hp'];
    $password = password_hash('123456', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (id, npm, nama, password, role, kelas, angkatan, no_hp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssis", $id, $npm, $nama, $password, $role, $kelas, $angkatan, $no_hp);
    
    if($stmt->execute()) {
        echo "<script>alert('Berhasil! Password default: 123456'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah user. NPM mungkin sudah ada.'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit;
}
?>