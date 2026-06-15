<?php
session_start();
include '../koneksi.php';
include 'fungsi_stok.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? ''; 

$where = "";
if ($kategori != '') {
    $where .= " AND items.id_kategori = '" . mysqli_real_escape_string($conn, $kategori) . "'";
}
if ($lokasi != '') {
    $where .= " AND items.id_lokasi = '" . mysqli_real_escape_string($conn, $lokasi) . "'";
}

$sql = mysqli_query($conn, "
    SELECT 
        items.*, 
        categories.nama AS kategori, 
        locations.nama AS lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    WHERE 1=1 $where
    ORDER BY items.nama ASC
");

$query_kat = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lok = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");

$user_role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Barang Inventaris</title>
</head>
<body>
    <h2>Data Barang / Alat Lab</h2>
    
    <form method="GET" action="data_barang.php">
        <label>Kategori:</label>
        <select name="kategori">
            <option value="">-- Semua Kategori --</option>
            <?php while($k = mysqli_fetch_assoc($query_kat)): ?>
                <option value="<?= htmlspecialchars($k['id']) ?>" <?= $kategori == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Lokasi:</label>
        <select name="lokasi">
            <option value="">-- Semua Lokasi --</option>
            <?php while($l = mysqli_fetch_assoc($query_lok)): ?>
                <option value="<?= htmlspecialchars($l['id']) ?>" <?= $lokasi == $l['id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nama']) ?></option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Filter</button>
        <a href="data_barang.php">Reset</a>
    </form>

    <br>

    <?php if ($user_role == 'Admin' || $user_role == 'Aslab'): ?>
        <a href="tambah_barang.php">Tambah Barang Baru</a>
    <?php endif; ?>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Stok</th>
                <th>Status Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(mysqli_num_rows($sql) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($sql)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['kode']) ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>
                        <td><?= htmlspecialchars($row['kategori'] ?? 'Tidak Ada') ?></td>
                        <td><?= htmlspecialchars($row['lokasi'] ?? 'Tidak Ada') ?></td>
                        <td><?= htmlspecialchars($row['stok']) ?></td>
                        <td><?= statusStok((int)$row['stok'], (int)$row['stok_minimum']); ?></td>
                        <td>
                            <a href="detail_barang.php?id=<?= urlencode($row['id']) ?>">Detail</a>
                            <?php if ($user_role == 'Admin' || $user_role == 'Aslab'): ?>
                                | <a href="edit_barang.php?id=<?= urlencode($row['id']) ?>">Edit</a>
                                | <a href="hapus_barang.php?id=<?= urlencode($row['id']) ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" align="center">Data barang kosong / tidak ditemukan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>