<?php
// index.php
require_once 'koneksi.php';

// Mengambil data dari tabel lokasi_barang diurutkan dari yang terbaru
$query = "SELECT * FROM lokasi_barang ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modul 3: Lokasi Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Lokasi Barang (Lab)</h2>
            <a href="tambahlokasi.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tambah Lokasi
            </a>
        </div>

        <?php if (isset($_GET['pesan'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline">
                    <?php 
                        if($_GET['pesan'] == 'tambah_sukses') echo "Data berhasil ditambahkan!";
                        elseif($_GET['pesan'] == 'edit_sukses') echo "Data berhasil diperbarui!";
                        elseif($_GET['pesan'] == 'hapus_sukses') echo "Data berhasil dihapus!";
                    ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="py-3 px-4 border-b">No</th>
                        <th class="py-3 px-4 border-b">Nama Lokasi</th>
                        <th class="py-3 px-4 border-b">Deskripsi</th>
                        <th class="py-3 px-4 border-b">Waktu Dibuat</th>
                        <th class="py-3 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result->num_rows > 0) {
                        $no = 1;
                        while($row = $result->fetch_assoc()) { 
                    ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b"><?= $no++; ?></td>
                                <td class="py-3 px-4 border-b font-medium"><?= htmlspecialchars($row['nama_lokasi']); ?></td>
                                <td class="py-3 px-4 border-b"><?= htmlspecialchars($row['deskripsi']); ?></td>
                                <td class="py-3 px-4 border-b"><?= htmlspecialchars($row['created_at']); ?></td>
                                <td class="py-3 px-4 border-b text-center">
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm py-1 px-3 rounded mr-1">Edit</a>
                                    <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')" class="bg-red-500 hover:bg-red-600 text-white text-sm py-1 px-3 rounded">Hapus</a>
                                </td>
                            </tr>
                    <?php 
                        }
                    } else { 
                    ?>
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center border-b text-gray-500">Belum ada data lokasi barang.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>