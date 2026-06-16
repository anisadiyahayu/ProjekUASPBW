<?php
include "auth.php";
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peminjaman = $_POST['id'];
    $aksi = $_POST['aksi']; 
    $catatan = $_POST['catatan'];

    if ($aksi === 'dikembalikan') {
        $query_tampil = mysqli_query($conn, "SELECT item_id, jumlah, status FROM peminjaman WHERE id = '$id_peminjaman'");
        $data_pinjam  = mysqli_fetch_assoc($query_tampil);

        if ($data_pinjam && $data_pinjam['status'] === 'disetujui') {
            $item_id = $data_pinjam['item_id'];
            $jumlah  = $data_pinjam['jumlah'];

            mysqli_query($conn, "UPDATE items SET stok = stok + $jumlah WHERE id = '$item_id'");

            mysqli_query($conn, "UPDATE peminjaman SET status = 'dikembalikan', catatan = '$catatan' WHERE id = '$id_peminjaman'");
        }
    }

    header("Location: admin_pengembalian.php");
    exit;
}
?>