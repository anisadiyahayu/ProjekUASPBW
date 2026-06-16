<?php
require_once __DIR__ . '/../auth/auth_check.php';
include  __DIR__ . "/../include/koneksi.php";
include __DIR__ . "/fungsi_stok.php";

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if ($id == '') {
    echo "<div class='p-4 text-red-600 font-medium text-center'>ID Barang tidak ditemukan.</div>";
    exit;
}

$query = mysqli_query($conn, "
    SELECT items.*, categories.nama AS kategori, locations.nama AS lokasi
    FROM items
    LEFT JOIN categories ON items.id_kategori = categories.id
    LEFT JOIN locations ON items.id_lokasi = locations.id
    WHERE items.id='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<div class='p-4 text-red-600 font-medium text-center'>Data barang tidak tersedia atau telah dihapus.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - <?= htmlspecialchars($data['nama']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-800 p-4 antialiased">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h2 class="text-xl font-bold tracking-tight flex items-center gap-2">
                <i class="fa-solid fa-box text-blue-400"></i> <?= htmlspecialchars($data['nama']) ?>
            </h2>
            <span class="text-xs font-mono bg-slate-800 px-2 py-1 rounded text-slate-300 border border-slate-700">
                ID: <?= htmlspecialchars($data['id']) ?>
            </span>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="flex flex-col items-center justify-center p-4 bg-slate-100 rounded-xl border border-slate-200">
                    <?php if (!empty($data['foto_barang']) && file_exists("../uploads/" . $data['foto_barang'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($data['foto_barang']) ?>" alt="Foto Barang" class="max-w-full h-auto max-h-48 object-contain rounded-lg shadow-sm">
                    <?php else: ?>
                        <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                            <i class="fa-solid fa-image text-4xl mb-2"></i>
                            <span class="text-xs">Tidak ada foto</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="md:col-span-2 space-y-3">
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Kode Barang</span>
                        <span class="text-sm font-semibold text-slate-800 col-span-2">: <?= htmlspecialchars($data['kode']) ?></span>
                    </div>
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Kategori</span>
                        <span class="text-sm font-medium text-slate-800 col-span-2">: <?= htmlspecialchars($data['kategori'] ?? 'Tidak ada Kategori') ?></span>
                    </div>
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Lokasi Lab</span>
                        <span class="text-sm font-medium text-slate-800 col-span-2">: <?= htmlspecialchars($data['lokasi'] ?? 'Belum Ditentukan') ?></span>
                    </div>
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Stok Tersedia</span>
                        <span class="text-sm font-bold text-slate-800 col-span-2">: <?= htmlspecialchars($data['stok']) ?> <span class="text-xs text-slate-400 font-normal"><?= htmlspecialchars($data['satuan'] ?? 'Unit') ?></span></span>
                    </div>
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Status Stok</span>
                        <span class="col-span-2 flex items-center gap-1">: 
                            <?php 
                            $status = statusStok($data['stok'], $data['stok_minimum']);
                            if ($status == 'Tersedia') {
                                echo '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-700">Tersedia</span>';
                            } elseif ($status == 'Menipis') {
                                echo '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menipis</span>';
                            } else {
                                echo '<span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">Habis</span>';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="grid grid-cols-3 py-2 border-b border-slate-100">
                        <span class="text-sm font-medium text-slate-400">Kondisi Alat</span>
                        <span class="col-span-2">: 
                            <span class="text-sm font-medium <?= $data['kondisi'] == 'Bagus' ? 'text-emerald-600' : 'text-rose-600' ?>">
                                <?= htmlspecialchars($data['kondisi']) ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Keterangan Tambahan</h4>
                <p class="text-sm text-slate-600 leading-relaxed"><?= !empty($data['keterangan']) ? nl2br(htmlspecialchars($data['keterangan'])) : 'Tidak ada catatan tambahan untuk barang ini.' ?></p>
            </div>

            <div>
                <h3 class="text-md font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Riwayat Aktivitas / Permintaan
                </h3>
                <div class="overflow-hidden border border-slate-100 rounded-xl shadow-sm">
                    <table class="w-full text-left border-collapse bg-white">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 text-xs font-semibold uppercase">
                                <th class="px-4 py-3">Pemohon</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Tanggal Log</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                            <?php
                            $riwayat = mysqli_query($conn, "
                                SELECT transactions.*, users.nama AS nama_user 
                                FROM transactions
                                LEFT JOIN users ON transactions.id_user = users.id
                                WHERE transactions.id_item='$id'
                                ORDER BY transactions.waktu_pinjam DESC
                            ");

                            if (mysqli_num_rows($riwayat) > 0):
                                while ($r = mysqli_fetch_assoc($riwayat)):
                            ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-4 py-3 font-medium text-slate-800"><?= htmlspecialchars($r['nama_user'] ?? 'User Terhapus') ?></td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-700"><?= htmlspecialchars($r['jumlah']) ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <?php if ($r['status'] == 'Disetujui' || $r['status'] == 'Selesai'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200"><?= htmlspecialchars($r['status']) ?></span>
                                        <?php elseif ($r['status'] == 'Pending'): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200"><?= htmlspecialchars($r['status']) ?></span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200"><?= htmlspecialchars($r['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-right text-xs text-slate-400"><?= date('d M Y H:i', strtotime($r['waktu_pinjam'])) ?></td>
                                </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-sm">
                                        Belum ada catatan transaksi/aktivitas peminjaman untuk barang ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>
</html>