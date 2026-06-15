<?php
session_start();
include '../koneksi.php';

$user_role = $_SESSION['role'] ?? '';
if ($user_role != 'Admin' && $user_role != 'Aslab') {
    die("Akses ditolak.");
}

if (empty($_GET['id'])) {
    die("ID barang tidak ditemukan.");
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM items WHERE id = '$id' LIMIT 1");

if (mysqli_num_rows($query) == 0) {
    die("Data barang tidak ditemukan.");
}

$data = mysqli_fetch_assoc($query);
$query_kategori = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lokasi   = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Barang</title>
</head>
<body>
    <h2>Edit Data Barang</h2>

    <form action="proses_edit_barang.php?id=<?= urlencode($data['id']) ?>" method="POST" enctype="multipart/form-data">
        <table cellpadding="6">
            <tr>
                <td>Kode Barang</td>
                <td><input type="text" name="kode" value="<?= htmlspecialchars($data['kode']) ?>" required></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required></td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>
                    <select name="id_kategori" required>
                        <?php while ($kat = mysqli_fetch_assoc($query_kategori)): ?>
                            <option value="<?= htmlspecialchars($kat['id']) ?>" <?= ($data['id_kategori'] == $kat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kat['nama']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>
                    <select name="id_lokasi" required>
                        <?php while ($lok = mysqli_fetch_assoc($query_lokasi)): ?>
                            <option value="<?= htmlspecialchars($lok['id']) ?>" <?= ($data['id_lokasi'] == $lok['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lok['nama']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Stok</td>
                <td><input type="number" name="stok" value="<?= $data['stok'] ?>" min="0" required></td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td><input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan']) ?>" required></td>
            </tr>
            <tr>
                <td>Kondisi</td>
                <td>
                    <select name="kondisi">
                        <option value="Bagus" <?= ($data['kondisi'] == 'Bagus') ? 'selected' : '' ?>>Bagus</option>
                        <option value="Rusak" <?= ($data['kondisi'] == 'Rusak') ? 'selected' : '' ?>>Rusak</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Stok Minimum</td>
                <td><input type="number" name="stok_minimum" value="<?= $data['stok_minimum'] ?>" min="0" required></td>
            </tr>
            <tr>
                <td>Foto Saat Ini</td>
                <td>
                    <?php if (!empty($data['foto_barang']) && file_exists("../uploads/" . $data['foto_barang'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($data['foto_barang']) ?>" width="100" alt="Foto"><br>
                    <?php endif; ?>
                    <input type="file" name="foto_barang" accept="image/*">
                    <small>(Kosongkan jika tidak ingin merubah foto)</small>
                </td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td><textarea name="keterangan" rows="4" cols="30"><?= htmlspecialchars($data['keterangan']) ?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit">Simpan Perubahan</button>
                    <a href="data_barang.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>