<?php
session_start();
include '../koneksi.php';

$user_role = $_SESSION['role'] ?? '';
if ($user_role != 'Admin' && $user_role != 'Aslab') {
    die("Akses ditolak.");
}

$id = $_GET['id'] ?? '';
if ($id == '') {
    die("ID Barang tidak ditemukan.");
}

$kode         = mysqli_real_escape_string($conn, $_POST['kode'] ?? '');
$nama         = mysqli_real_escape_string($conn, $_POST['nama'] ?? '');
$id_kategori  = mysqli_real_escape_string($conn, $_POST['id_kategori'] ?? null);
$id_lokasi    = mysqli_real_escape_string($conn, $_POST['id_lokasi'] ?? null);
$stok         = (int)($_POST['stok'] ?? 0);
$satuan       = mysqli_real_escape_string($conn, $_POST['satuan'] ?? '');
$kondisi      = mysqli_real_escape_string($conn, $_POST['kondisi'] ?? 'Bagus');
$stok_minimum = (int)($_POST['stok_minimum'] ?? 0);
$keterangan   = mysqli_real_escape_string($conn, $_POST['keterangan'] ?? '');

$id = mysqli_real_escape_string($conn, $id);
$q_lama = mysqli_query($conn, "SELECT foto_barang FROM items WHERE id = '$id'");
$d_lama = mysqli_fetch_assoc($q_lama);
$foto   = $d_lama['foto_barang'] ?? '';

if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['name'] != '') {
    $nama_file     = $_FILES['foto_barang']['name'];
    $error_file    = $_FILES['foto_barang']['error'];
    $tmp_file      = $_FILES['foto_barang']['tmp_name'];
    
    $ekstensi_file = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $ekstensi_boleh = ['jpg', 'jpeg', 'png', 'webp'];

    if (in_array($ekstensi_file, $ekstensi_boleh) && $error_file === 0) {
        $foto_baru = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ekstensi_file;
        if (move_uploaded_file($tmp_file, "../uploads/" . $foto_baru)) {
            if (!empty($foto) && file_exists("../uploads/" . $foto)) {
                unlink("../uploads/" . $foto);
            }
            $foto = $foto_baru;
        }
    }
}

$query = "UPDATE items SET kode=?, nama=?, id_kategori=?, id_lokasi=?, stok=?, satuan=?, kondisi=?, foto_barang=?, stok_minimum=?, keterangan=? WHERE id=?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssisssiss", $kode, $nama, $id_kategori, $id_lokasi, $stok, $satuan, $kondisi, $foto, $stok_minimum, $keterangan, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header("Location: data_barang.php");
exit();
?>