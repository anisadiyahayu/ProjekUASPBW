<?php
include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'];
$id_user = $_SESSION['user_id'] ?? $_SESSION['id'];

$query = mysqli_query($conn,"
SELECT
    transactions.*,
    items.nama AS nama_barang
FROM transactions
LEFT JOIN items ON transactions.id_item = items.id
WHERE transactions.id_user = '$id_user'
ORDER BY transactions.id DESC
");

if(!$query){
    die(mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <?php $current_page = 'riwayat'; ?>
        <?php include '../template/sidebar.php'; ?>

        <div id="main-content" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include '../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Riwayat Transaksi</h1>
                    <p class="text-sm text-slate-500 mt-1">Daftar lengkap rekam jejak aktivitas peminjaman Anda.</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600">
                                    <th class="px-6 py-4 font-semibold">Nama Barang</th>
                                    <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                                    <th class="px-6 py-4 font-semibold">Catatan Keperluan</th>
                                    <th class="px-6 py-4 font-semibold">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 font-semibold">Tanggal Kembali</th>
                                    <th class="px-6 py-4 font-semibold text-center">Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td class="px-6 py-4 text-center font-semibold text-slate-700"><?= htmlspecialchars($row['jumlah']) ?></td>
                                    <td class="px-6 py-4 text-slate-600 max-w-[200px] truncate" title="<?= htmlspecialchars($row['keterangan'] ?? '') ?>"><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= !empty($row['waktu_pinjam']) ? date('d M Y', strtotime($row['waktu_pinjam'])) : '-' ?></td>
                                    <td class="px-6 py-4 text-slate-500"><?= (!empty($row['waktu_kembali']) && $row['waktu_kembali'] != '0000-00-00 00:00:00') ? date('d M Y', strtotime($row['waktu_kembali'])) : '-' ?></td>
                                    <td class="px-6 py-4 text-center">
                                        <?php 
                                        $s = strtolower($row['status']);
                                        if($s == 'pending' || $s == 'menunggu'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Menunggu</span>
                                        <?php elseif($s == 'disetujui' || $s == 'sedang dipinjam'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">Dipinjam</span>
                                        <?php elseif($s == 'ditolak'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">Ditolak</span>
                                        <?php elseif($s == 'selesai' || $s == 'dikembalikan'): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200"><?= htmlspecialchars($row['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                
                                <?php if(mysqli_num_rows($query) == 0): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
                                            <p>Belum ada rekaman riwayat transaksi untuk akun Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="../include/script.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>