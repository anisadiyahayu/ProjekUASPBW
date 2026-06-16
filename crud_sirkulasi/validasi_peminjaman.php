<?php
include "auth.php";
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peminjaman = $_POST['id'];
    $aksi = $_POST['aksi']; 
    $catatan = $_POST['catatan'];

    $query_tampil = mysqli_query($conn, "SELECT item_id, jumlah FROM peminjaman WHERE id = '$id_peminjaman'");
    $data_pinjam  = mysqli_fetch_assoc($query_tampil);
    
    if ($data_pinjam) {
        $item_id = $data_pinjam['item_id'];
        $jumlah  = $data_pinjam['jumlah'];

        if ($aksi === 'disetujui') {
            $query_barang = mysqli_query($conn, "SELECT stok FROM items WHERE id = '$item_id'");
            $data_barang  = mysqli_fetch_assoc($query_barang);

            if ($data_barang['stok'] >= $jumlah) {
                mysqli_query($conn, "UPDATE items SET stok = stok - $jumlah WHERE id = '$item_id'");
                
                mysqli_query($conn, "UPDATE peminjaman SET status = 'disetujui', catatan = '$catatan' WHERE id = '$id_peminjaman'");
            } else {
                echo "<script>alert('Gagal setujui! Stok barang tidak mencukupi.'); window.location.href='admin_peminjaman.php';</script>";
                exit;
            }
        } else {
            mysqli_query($conn, "UPDATE peminjaman SET status = 'ditolak', catatan = '$catatan' WHERE id = '$id_peminjaman'");
        }
    }

    header("Location: admin_peminjaman.php");
    exit;
}
?>