<?php
session_start();
require_once '../auth/auth_check.php';
include "../include/koneksi.php";

$id_transaksi = $_GET['id'] ?? '';

if (!empty($id_transaksi)) {
    $id_transaksi = mysqli_real_escape_string($conn, $id_transaksi);
    $id_admin = $_SESSION['user_id']; // ID Admin yang memvalidasi

    $query_t = mysqli_query($conn, "SELECT * FROM transactions WHERE id = '$id_transaksi' AND status = 'Sedang Dipinjam'");
    $transaksi = mysqli_fetch_assoc($query_t);

    if ($transaksi) {
        $id_item = $transaksi['id_item'];
        $jumlah  = $transaksi['jumlah'];
        $waktu_sekarang = date('Y-m-d H:i:s');

        mysqli_begin_transaction($conn);

        try {
            $update_transaksi = mysqli_query($conn, "
                UPDATE transactions 
                SET status = 'Selesai', 
                    waktu_kembali = '$waktu_sekarang', 
                    approved_by = '$id_admin' 
                WHERE id = '$id_transaksi'
            ");

            $update_stok = mysqli_query($conn, "
                UPDATE items 
                SET stok = stok + $jumlah 
                WHERE id = '$id_item'
            ");

            $nama_admin = $_SESSION['nama'];
            $log_aktivitas = mysqli_query($conn, "
                INSERT INTO activity_logs (aktivitas, id_user, role) 
                VALUES ('Menyetujui pengembalian barang nomor transaksi $id_transaksi', '$id_admin', '".$_SESSION['role']."')
            ");

            mysqli_commit($conn);
            echo "<script>alert('Pengembalian barang berhasil diproses!'); window.location='pengembalian_barang.php';</script>";

        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<script>alert('Gagal memproses pengembalian barang!'); window.location='pengembalian_barang.php';</script>";
        }
    } else {
        echo "<script>alert('Data transaksi tidak valid atau sudah diproses!'); window.location='pengembalian_barang.php';</script>";
    }
} else {
    echo "<script>window.location='pengembalian_barang.php';</script>";
}
?>