<?php
session_start();

$user_role = $_SESSION['role'] ?? '';
if ($user_role != 'Admin' && $user_role != 'Aslab') {
    die("Akses ditolak");
}

include '../koneksi.php';
$id = $_GET['id'] ?? '';

if ($id != '') {
    $id = mysqli_real_escape_string($conn, $id);
    
    $q_foto = mysqli_query($conn, "SELECT foto_barang FROM items WHERE id = '$id'");
    if ($data = mysqli_fetch_assoc($q_foto)) {
        if (!empty($data['foto_barang']) && file_exists("../uploads/" . $data['foto_barang'])) {
            unlink("../uploads/" . $data['foto_barang']);
        }
    }

    $query = "DELETE FROM items WHERE id = ?";
    $stmt  = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

header("Location: data_barang.php");
exit();
?>