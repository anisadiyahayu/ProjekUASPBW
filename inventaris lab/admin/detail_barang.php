<?php
session_start();
include '../koneksi.php';
include 'fungsi_stok.php';

$id = $_GET['id'] ?? '';
if ($id == '') {
    die("ID Barang tidak ditemukan.");
}

$query_barang = "
    SELECT items.*, categories.nama AS kategori, locations.nama AS lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    WHERE items.id = ?
";

$stmt_barang = mysqli_prepare($conn, $query_barang);
$data = [];

if ($stmt_barang) {
    mysqli_stmt_bind_param($stmt_barang, "s", $id);
    mysqli_stmt_execute($stmt_barang);
    $result = mysqli_stmt_get_result($stmt_barang);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_barang);
}

if (!$data) {
    die("Data barang tidak ditemukan.");
}

// Ambil riwayat peminjaman dari tabel transaksi
$query_trx = "SELECT * FROM transactions WHERE id_item = ? ORDER BY waktu_pinjam DESC";
$stmt_trx  = mysqli_prepare($conn, $query_trx);
$result_trx = null;

if ($stmt_trx) {
    mysqli_stmt_bind_param($stmt_trx, "s", $id);
    mysqli_stmt_execute($stmt_trx);
    $result_trx = mysqli_stmt_get_result($stmt_trx);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Detail Barang</title>
</head>
<body>
    <h2>Detail Alat Lab: <?= htmlspecialchars($data['nama']) ?></h2>

    <p>Kode : <?= htmlspecialchars($data['kode']) ?></p>
    <p>Kategori : <?= htmlspecialchars($data['kategori'] ?? 'Tidak ada') ?></p>
    <p>Lokasi : <?= htmlspecialchars($data['lokasi'] ?? 'Tidak ada') ?></p>
    <p>Stok : <?= htmlspecialchars($data['stok']) ?> <?= htmlspecialchars($data['satuan'] ?? '') ?></p>
    <p>Status : <?= statusStok((int)$data['stok'], (int)$data['stok_minimum']); ?></p>
    <p>Kondisi : <?= htmlspecialchars($data['kondisi']) ?></p>
    <p>Keterangan : <?= nl2br(htmlspecialchars($data['keterangan'] ?? '-')) ?></p>
    <p>
        <strong>Foto Barang:</strong><br>
        <?php if (!empty($data['foto_barang']) && file_exists("../uploads/" . $data['foto_barang'])): ?>
            <img src="../uploads/<?= htmlspecialchars($data['foto_barang']) ?>" width="200" alt="Foto">
        <?php else: ?>
            <em>(Tidak ada lampiran foto)</em>
        <?php endif; ?>
    </p>

    <h3>Riwayat Permintaan / Peminjaman</h3>

    <?php if ($result_trx && mysqli_num_rows($result_trx) > 0): ?>
        <?php while ($t = mysqli_fetch_assoc($result_trx)): ?>
            <p>
                Jumlah : <?= htmlspecialchars($t['jumlah']) ?> 
                | 
                Status : <?= htmlspecialchars($t['status']) ?> 
                | 
                Tanggal : <?= htmlspecialchars($t['waktu_pinjam']) ?>
            </p>
        <?php endwhile; ?>
    <?php else: ?>
        <p><em>Belum ada riwayat permintaan/peminjaman untuk barang ini.</em></p>
    <?php endif; ?>

    <br>
    <a href="data_barang.php">Kembali ke Daftar</a>
</body>
</html>
<?php 
if ($stmt_trx) {
    mysqli_stmt_close($stmt_trx);
}
?>