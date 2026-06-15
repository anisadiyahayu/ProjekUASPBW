<?php
session_start();
include '../koneksi.php';

$user_role = $_SESSION['role'] ?? '';
if ($user_role != 'Admin' && $user_role != 'Aslab') {
    die("Akses ditolak. Anda tidak memiliki wewenang.");
}

$query_kategori = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lokasi   = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Tambah Barang / Alat Lab Baru</h2>

    <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data">
        <table cellpadding="6">
            <tr>
                <td>Kode Barang</td>
                <td><input type="text" name="kode" required placeholder="Contoh: BRG-001"></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><input type="text" name="nama" required></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>
                    <select name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
                            <option value="<?= htmlspecialchars($kat['id']) ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>
                    <select name="id_lokasi" required>
                        <option value="">-- Pilih Lokasi --</option>
                        <?php while ($lok = mysqli_fetch_assoc($query_lokasi)): ?>
                            <option value="<?= htmlspecialchars($lok['id']) ?>"><?= htmlspecialchars($lok['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Stok Awal</td>
                <td><input type="number" name="stok" min="0" required></td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td><input type="text" name="satuan" required placeholder="Pcs / Unit / Box"></td>
            </tr>
            <tr>
                <td>Kondisi</td>
                <td>
                    <select name="kondisi">
                        <option value="Bagus">Bagus</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Stok Minimum</td>
                <td><input type="number" name="stok_minimum" min="0" required></td>
            </tr>
            <tr>
                <td>Foto Barang</td>
                <td><input type="file" name="foto_barang" accept="image/*" required></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td><textarea name="keterangan" rows="4" cols="30"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan</button>
                    <a href="data_barang.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>