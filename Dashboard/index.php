<?php
// index.php (Letakkan di dalam folder /dashboard)
require_once '../auth/auth_check.php';
include "../include/koneksi.php";

// ========================================================================
// LOGIKA READ (R) - Mengambil data agregasi dari Database Nyata
// ========================================================================

// 1. Hitung Total untuk Kartu Ringkasan
$total_barang    = $conn->query("SELECT COUNT(*) FROM items")->fetch_row()[0] ?? 0;
$total_kategori  = $conn->query("SELECT COUNT(*) FROM categories")->fetch_row()[0] ?? 0;
$total_lokasi    = $conn->query("SELECT COUNT(*) FROM locations")->fetch_row()[0] ?? 0;
$total_user      = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$total_menunggu  = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='Menunggu'")->fetch_row()[0] ?? 0;
$total_dipinjam  = $conn->query("SELECT COUNT(*) FROM transactions WHERE status='Sedang Dipinjam'")->fetch_row()[0] ?? 0;

// 2. Query Permintaan Terbaru (Menunggu)
$q_permintaan = $conn->query("
    SELECT t.id, u.nama, u.npm as nim, i.nama as barang, t.jumlah, t.waktu_pinjam as waktu, t.status 
    FROM transactions t
    JOIN users u ON t.id_user = u.id
    JOIN items i ON t.id_item = i.id
    ORDER BY t.waktu_pinjam DESC 
    LIMIT 4
");

// 3. Query Pengembalian Terbaru
$q_pengembalian = $conn->query("
    SELECT p.id, u.nama, u.npm as nim, i.nama as barang, t.waktu_pinjam as tgl_pinjam, p.tanggal_pengajuan as tgl_kembali, p.status_pengembalian as status
    FROM pengembalian p
    JOIN transactions t ON p.id_transaksi = t.id
    JOIN users u ON t.id_user = u.id
    JOIN items i ON t.id_item = i.id
    ORDER BY p.tanggal_pengajuan DESC
    LIMIT 4
");

// 4. Query Aktivitas Terbaru
$q_aktivitas = $conn->query("
    SELECT a.aktivitas, a.created_at, u.nama 
    FROM activity_logs a
    JOIN users u ON a.id_user = u.id
    ORDER BY a.created_at DESC
    LIMIT 5
");

// Fungsi Helper untuk format waktu (contoh: "10 menit lalu")
function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' tahun lalu';
    if ($diff->m > 0) return $diff->m . ' bulan lalu';
    if ($diff->d > 0) return $diff->d . ' hari lalu';
    if ($diff->h > 0) return $diff->h . ' jam lalu';
    if ($diff->i > 0) return $diff->i . ' menit lalu';
    return 'Baru saja';
}
?>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="../include/style_tailwind.css">
<link rel="stylesheet" href="../include/style_sidebar.css">
<div class="min-h-screen bg-background">
    <?php $current_page = 'dashboard'; ?>
    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab') {
        include __DIR__ . '/../template/sidebar_admin.php';
    } else {
        include __DIR__ . '/../template/sidebar.php';
    } ?>
    <div id="main-content" class="transition-all duration-300 ml-64">
        <?php include __DIR__ . '/../template/header.php'; ?>
        <div class="max-w-7xl mx-auto space-y-6 p-8">

            <!-- Bagian Judul -->
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-slate-800 mb-1">Dashboard</h1>
                <p class="text-slate-500 text-sm">Ringkasan sistem manajemen inventaris laboratorium</p>
            </div>

            <!-- 6 KARTU RINGKASAN (Grid 3 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Barang</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_barang); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-blue-500/30">
                        <i data-feather="box" class="w-6 h-6"></i>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Kategori</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_kategori); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-purple-500/30">
                        <i data-feather="folder" class="w-6 h-6"></i>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Lokasi</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_lokasi); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-green-500/30">
                        <i data-feather="map-pin" class="w-6 h-6"></i>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total User</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_user); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-orange-500/30">
                        <i data-feather="users" class="w-6 h-6"></i>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Permintaan Menunggu</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_menunggu); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-yellow-500/30">
                        <i data-feather="clock" class="w-6 h-6"></i>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Barang Sedang Dipinjam</p>
                        <h3 class="text-3xl font-bold text-slate-800"><?= number_format($total_dipinjam); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-md shadow-red-500/30">
                        <i data-feather="package" class="w-6 h-6"></i>
                    </div>
                </div>

            </div>

            <!-- BANNER LAPORAN -->
            <div class="bg-blue-600 rounded-2xl p-6 shadow-lg shadow-blue-600/20 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-white">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <i data-feather="file-text" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold">Laporan Inventaris</h3>
                        <p class="text-blue-100 text-sm">Buat dan kelola laporan inventaris laboratorium</p>
                    </div>
                </div>
                <a href="../laporan_inventaris/laporan.php" class="bg-white text-blue-600 font-semibold py-2.5 px-6 rounded-lg hover:bg-slate-50 transition-colors shadow-sm whitespace-nowrap">
                    Akses Laporan
                </a>
            </div>

            <!-- TABEL PERMINTAAN TERBARU -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg">Permintaan Terbaru</h3>
                    <a href="../peminjaman/index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-600 text-sm font-semibold border-b border-slate-100 bg-slate-50/50">
                                <th class="py-4 px-6">Peminjam</th>
                                <th class="py-4 px-6">Barang</th>
                                <th class="py-4 px-6">Jumlah</th>
                                <th class="py-4 px-6">Waktu</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            <?php
                            if ($q_permintaan && $q_permintaan->num_rows > 0):
                                while ($p = $q_permintaan->fetch_assoc()):
                            ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6">
                                            <p class="font-semibold text-slate-800"><?= htmlspecialchars($p['nama']); ?></p>
                                            <p class="text-xs text-slate-500"><?= htmlspecialchars($p['nim']); ?></p>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600"><?= htmlspecialchars($p['barang']); ?></td>
                                        <td class="py-4 px-6 font-semibold text-slate-700"><?= htmlspecialchars($p['jumlah']); ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('Y-m-d H:i', strtotime($p['waktu'])); ?></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                <?= $p['status'] == 'Menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-slate-100 text-slate-700' ?>">
                                                <?= htmlspecialchars($p['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-center space-x-2 whitespace-nowrap">
                                            <a href="../peminjaman/setujui.php?id=<?= $p['id']; ?>" class="text-green-600 font-medium hover:bg-green-50 px-3 py-1.5 rounded-md transition-colors">Setujui</a>
                                            <a href="../peminjaman/tolak.php?id=<?= $p['id']; ?>" class="text-red-600 font-medium hover:bg-red-50 px-3 py-1.5 rounded-md transition-colors">Tolak</a>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500 font-medium">Belum ada permintaan peminjaman.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- TABEL PENGEMBALIAN TERBARU -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg">Pengembalian Terbaru</h3>
                    <a href="../pengembalian/index.php" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-600 text-sm font-semibold border-b border-slate-100 bg-slate-50/50">
                                <th class="py-4 px-6">Peminjam</th>
                                <th class="py-4 px-6">Barang</th>
                                <th class="py-4 px-6">Tanggal Pinjam</th>
                                <th class="py-4 px-6">Tanggal Kembali</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            <?php
                            if ($q_pengembalian && $q_pengembalian->num_rows > 0):
                                while ($p = $q_pengembalian->fetch_assoc()):
                            ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6">
                                            <p class="font-semibold text-slate-800"><?= htmlspecialchars($p['nama']); ?></p>
                                            <p class="text-xs text-slate-500"><?= htmlspecialchars($p['nim']); ?></p>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600"><?= htmlspecialchars($p['barang']); ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('Y-m-d', strtotime($p['tgl_pinjam'])); ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('Y-m-d', strtotime($p['tgl_kembali'])); ?></td>
                                        <td class="py-4 px-6 text-center">
                                            <?php if ($p['status'] == 'Selesai'): ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                    Selesai
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                    Menunggu Verifikasi
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <?php if ($p['status'] != 'Selesai'): ?>
                                                <a href="../pengembalian/verifikasi.php?id=<?= $p['id']; ?>" class="text-blue-600 font-medium hover:bg-blue-50 px-3 py-1.5 rounded-md transition-colors">Verifikasi</a>
                                            <?php else: ?>
                                                <span class="text-slate-400">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-500 font-medium">Belum ada pengembalian terbaru.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- LIST AKTIVITAS TERBARU -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-lg">Aktivitas Terbaru</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    <?php
                    if ($q_aktivitas && $q_aktivitas->num_rows > 0):
                        $i = 0;
                        while ($a = $q_aktivitas->fetch_assoc()):
                            // Mengambil huruf pertama dari nama
                            $inisial = strtoupper(substr($a['nama'], 0, 1));
                            // Memberikan warna selang-seling untuk inisial (Indigo/Slate)
                            $color = ($i % 2 == 0) ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700';
                    ?>
                            <div class="px-6 py-4 flex items-start gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 <?= $color; ?>">
                                    <?= $inisial; ?>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-slate-700">
                                        <span class="font-semibold text-slate-900"><?= htmlspecialchars($a['nama']); ?></span>
                                        <?= htmlspecialchars($a['aktivitas']); ?>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1"><?= time_elapsed_string($a['created_at']); ?></p>
                                </div>
                            </div>
                        <?php
                            $i++;
                        endwhile;
                    else:
                        ?>
                        <div class="px-6 py-8 text-center text-slate-500 font-medium">Belum ada aktivitas yang terekam di sistem.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>

</div>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    // Inisialisasi Feather Icons
    feather.replace();
</script>
<script src="../include/script.js"></script>