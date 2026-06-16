<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$q_aktivitas = $conn->query("
    SELECT a.aktivitas, a.created_at, u.nama 
    FROM activity_logs a
    LEFT JOIN users u ON a.user_id = u.id
    ORDER BY a.created_at DESC
    LIMIT 10
");

$q_peminjaman = $conn->query("
    SELECT t.id, u.nama, u.npm as nim, i.nama_barang as barang, t.jumlah, t.tanggal_pinjam as waktu, t.status 
    FROM peminjaman t
    JOIN users u ON t.user_id = u.id
    JOIN items i ON t.item_id = i.id
    ORDER BY t.tanggal_pinjam DESC 
    LIMIT 10
");

$q_pengembalian = $conn->query("
    SELECT p.id, u.nama, u.npm as nim, i.nama_barang as barang, t.tanggal_pinjam as tgl_pinjam, p.tanggal_pengembalian as tgl_kembali, p.status as status
    FROM pengembalian p
    JOIN peminjaman t ON p.peminjaman_id  = t.id
    JOIN users u ON t.user_id = u.id
    JOIN items i ON t.item_id = i.id
    ORDER BY p.tanggal_pengembalian DESC
    LIMIT 10
");

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
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Aktivitas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F1F5F9;
        }
    </style>

</head>

<body>

    <aside class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">

        <div class="h-full flex flex-col">

            <div class="p-5 border-b border-blue-800">

                <div class="flex items-center gap-3">

                    <div class="w-9 h-9 rounded-lg bg-[#3B82F6] flex items-center justify-center">

                        <i data-lucide="box" class="w-5 h-5"></i>

                    </div>

                    <div>

                        <h1 class="font-semibold">
                            Lab Inventory
                        </h1>

                        <p class="text-xs text-blue-200">
                            Admin Panel
                        </p>

                    </div>

                </div>

            </div>

            <nav class="flex-1 px-2 py-4">

                <div class="space-y-1">

                    <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </a>

                    <a href="barang.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="package" class="w-4 h-4"></i>
                        Data Barang
                    </a>

                    <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="folder-tree" class="w-4 h-4"></i>
                        Kategori Barang
                    </a>

                    <a href="lokasi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Lokasi Penyimpanan
                    </a>

                    <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="users" class="w-4 h-4"></i>
                        Data User
                    </a>

                    <a href="admin_peminjaman.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="package-check" class="w-4 h-4"></i>
                        Peminjaman Barang
                    </a>

                    <a href="admin_pengembalian.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                        Pengembalian Barang
                    </a>

                    <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        Laporan Inventaris
                    </a>

                    <a href="aktivitas.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Riwayat Aktivitas
                    </a>

                    <a href="profil_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profil
                    </a>

                </div>

            </nav>

            <div class="p-4 border-t border-blue-800">

                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91]">

                    <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>

                    <span class="group-hover:text-red-500">
                        Logout
                    </span>

                </a>

            </div>

        </div>

    </aside>

    <div class="ml-64 flex-1 flex flex-col min-h-screen">

        <header
            class="bg-white border-b h-[56px] px-6 flex justify-between items-center">

            <div class="flex items-center gap-4">

                <button>
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>

                <div class="relative w-[280px]">

                    <i
                        data-lucide="search"
                        class="absolute left-3 top-2.5 w-4 h-4 text-slate-400">
                    </i>

                    <input
                        type="text"
                        placeholder="Cari barang, user, atau aktivitas..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">

                </div>

            </div>

            <div class="flex items-center gap-5">

                <div class="relative">

                    <i data-lucide="bell" class="w-5 h-5"></i>

                    <span
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                        5
                    </span>

                </div>

                <div class="flex items-center gap-3">

                    <div class="text-right">

                        <h4 class="text-sm font-semibold">
                            <?= htmlspecialchars($nama) ?>
                        </h4>

                        <p class="text-[11px] text-slate-500">
                            Administrator
                        </p>

                    </div>

                    <div
                        class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">

                        <?= strtoupper(substr($nama, 0, 2)) ?>

                    </div>

                </div>

            </div>

        </header>

        <main class="p-6 space-y-8 flex-1">

            <div>
                <h1 class="text-2xl font-bold text-slate-800 mb-1">Riwayat Aktivitas</h1>
                <p class="text-slate-500 text-sm">Log semua aktivitas peminjaman, pengembalian, dan perubahan data sistem laboratorium</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Log Sistem & Perubahan Data</h3>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php
                    if ($q_aktivitas && $q_aktivitas->num_rows > 0):
                        $i = 0;
                        while ($a = $q_aktivitas->fetch_assoc()):
                            $nama_user = $a['nama'] ?? 'Sistem';
                            $inisial = strtoupper(substr($nama_user, 0, 1));
                            $color = ($i % 2 == 0) ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200';
                    ?>
                            <div class="px-6 py-4 flex items-start gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm shrink-0 <?= $color; ?>">
                                    <?= $inisial; ?>
                                </div>
                                <div class="flex-1 pt-0.5">
                                    <p class="text-[15px] text-slate-700 leading-snug">
                                        <span class="font-bold text-slate-900"><?= htmlspecialchars($nama_user); ?></span>
                                        <?= htmlspecialchars($a['aktivitas']); ?>
                                    </p>
                                    <p class="text-xs text-slate-500 mt-1 font-medium"><?= time_elapsed_string($a['created_at']); ?></p>
                                </div>
                            </div>
                    <?php
                            $i++;
                        endwhile;
                    else: ?>
                        <p class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada aktivitas.</p>
                    <?php endif; ?>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/30 flex items-center gap-3">
                    <i data-lucide="package" class="w-5 h-5 text-amber-500"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Riwayat Peminjaman</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-slate-600 text-[13px] font-semibold border-b">
                            <tr>
                                <th class="py-4 px-6">Peminjam</th>
                                <th class="py-4 px-6">Barang</th>
                                <th class="py-4 px-6 text-center">Jumlah</th>
                                <th class="py-4 px-6">Waktu</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if ($q_peminjaman && $q_peminjaman->num_rows > 0): ?>
                                <?php while ($p = $q_peminjaman->fetch_assoc()):
                                    $status_bg = ($p['status'] == 'Disetujui' || $p['status'] == 'disetujui') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
                                ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="font-semibold text-slate-800"><?= htmlspecialchars($p['nama'] ?? ''); ?></div>
                                            <div class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($p['nim'] ?? ''); ?></div>
                                        </td>
                                        <td class="py-4 px-6 font-medium text-slate-600"><?= htmlspecialchars($p['barang'] ?? ''); ?></td>
                                        <td class="py-4 px-6 font-bold text-slate-800 text-center"><?= $p['jumlah'] ?? ''; ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('d M Y H:i', strtotime($p['waktu'] ?? '')); ?></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $status_bg ?>">
                                                <?= ucfirst(htmlspecialchars($p['status'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500 font-medium">Belum ada riwayat peminjaman.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/30 flex items-center gap-3">
                    <i data-lucide="rotate-ccw" class="w-5 h-5 text-emerald-500"></i>
                    <h3 class="font-bold text-slate-800 text-lg">Riwayat Pengembalian</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-slate-600 text-[13px] font-semibold border-b">
                            <tr>
                                <th class="py-4 px-6">Peminjam</th>
                                <th class="py-4 px-6">Barang</th>
                                <th class="py-4 px-6">Tgl Pinjam</th>
                                <th class="py-4 px-6">Tgl Kembali</th>
                                <th class="py-4 px-6 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            <?php if ($q_pengembalian && $q_pengembalian->num_rows > 0): ?>
                                <?php while ($p = $q_pengembalian->fetch_assoc()):
                                    $status_bg = ($p['status'] == 'Selesai' || $p['status'] == 'dikembalikan' || $p['status'] == 'Selesai') ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
                                ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="font-semibold text-slate-800"><?= htmlspecialchars($p['nama']); ?></div>
                                            <div class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($p['nim']); ?></div>
                                        </td>
                                        <td class="py-4 px-6 font-medium text-slate-600"><?= htmlspecialchars($p['barang']); ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('d M Y', strtotime($p['tgl_pinjam'])); ?></td>
                                        <td class="py-4 px-6 text-slate-500"><?= date('d M Y', strtotime($p['tgl_kembali'])); ?></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $status_bg ?>">
                                                <?= ucfirst(htmlspecialchars($p['status'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500 font-medium">Belum ada riwayat pengembalian.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </main>

    </div>

    <script>
        lucide.createIcons();
    </script>

</body>

</html>