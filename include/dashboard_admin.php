<?php
include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$totalBarang = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM items"));
$totalKategori = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories"));
$totalLokasi = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM locations"));
$totalUser = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$totalPending = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status='pending'"));
$totalDipinjam = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM peminjaman WHERE status='disetujui'"));

$permintaan = mysqli_query($conn, "SELECT peminjaman.*, users.nama, users.npm, items.nama_barang FROM peminjaman LEFT JOIN users ON peminjaman.user_id = users.id LEFT JOIN items ON peminjaman.item_id = items.id ORDER BY peminjaman.id DESC LIMIT 5");
$pengembalian = mysqli_query($conn, "SELECT pengembalian.*, users.nama, users.npm, items.nama_barang, peminjaman.tanggal_pinjam FROM pengembalian LEFT JOIN peminjaman ON pengembalian.peminjaman_id = peminjaman.id LEFT JOIN users ON peminjaman.user_id = users.id LEFT JOIN items ON peminjaman.item_id = items.id ORDER BY pengembalian.id DESC LIMIT 5");
$aktivitas = mysqli_query($conn, "SELECT activity_logs.*, users.nama FROM activity_logs LEFT JOIN users ON activity_logs.user_id = users.id ORDER BY activity_logs.id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        body { background: #f1f5f9; }
        .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .table-row:hover { background: #f8fafc; }
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
                        <h1 class="font-semibold">Lab Inventory</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-2 py-4">
                <div class="space-y-1">
                    <a href="dashboard_admin.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="barang.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="package" class="w-4 h-4"></i> Data Barang
                    </a>
                    <a href="kategori.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="folder-tree" class="w-4 h-4"></i> Kategori Barang
                    </a>
                    <a href="lokasi.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i> Lokasi Penyimpanan
                    </a>
                    <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="users" class="w-4 h-4"></i> Data User
                    </a>
                    <a href="admin_peminjaman.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="package-check" class="w-4 h-4"></i> Peminjaman Barang
                    </a>
                    <a href="admin_pengembalian.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Pengembalian Barang
                    </a>
                    <a href="laporan.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="file-text" class="w-4 h-4"></i> Laporan Inventaris
                    </a>
                    <a href="aktivitas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="history" class="w-4 h-4"></i> Riwayat Aktivitas
                    </a>
                    <a href="profil_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="user" class="w-4 h-4"></i> Profil
                    </a>
                </div>
            </nav>
            <div class="p-4 border-t border-blue-800">
                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all">
                    <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                    <span class="group-hover:text-red-500">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-white border-b h-[56px] px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button><i data-lucide="x" class="w-4 h-4 text-slate-500"></i></button>
                <div class="relative w-[280px]">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Cari barang, user, atau aktivitas..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">
                </div>
            </div>
            <div class="flex items-center gap-5">
                <div class="relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">5</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <h4 class="text-sm font-semibold"><?= htmlspecialchars($nama) ?></h4>
                        <p class="text-[11px] text-slate-500">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)) ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="mb-6">
                <h1 class="text-[20px] font-semibold text-slate-800">Dashboard</h1>
                <p class="text-slate-500 text-sm">Ringkasan sistem manajemen inventaris laboratorium</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Total Barang</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalBarang ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#3B82F6] text-white flex items-center justify-center"><i data-lucide="package" class="w-5 h-5"></i></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Total Kategori</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalKategori ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#A855F7] text-white flex items-center justify-center"><i data-lucide="folder-tree" class="w-5 h-5"></i></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Total Lokasi</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalLokasi ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#22C55E] text-white flex items-center justify-center"><i data-lucide="map-pin" class="w-5 h-5"></i></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Total User</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalUser ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#F97316] text-white flex items-center justify-center"><i data-lucide="users" class="w-5 h-5"></i></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Permintaan Menunggu</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalPending ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#EAB308] text-white flex items-center justify-center"><i data-lucide="clock-3" class="w-5 h-5"></i></div>
                    </div>
                </div>
                <div class="card p-5">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-slate-500">Barang Sedang Dipinjam</p>
                            <h2 class="text-4xl font-bold mt-2"><?= $totalDipinjam ?></h2>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-[#EF4444] text-white flex items-center justify-center"><i data-lucide="package-check" class="w-5 h-5"></i></div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-[#3B82F6] to-[#2563EB] rounded-xl shadow text-white p-5 flex justify-between items-center mb-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center"><i data-lucide="file-text" class="w-6 h-6"></i></div>
                    <div>
                        <h3 class="font-semibold text-lg">Laporan Inventaris</h3>
                        <p class="text-sm text-blue-100">Buat dan kelola laporan inventaris laboratorium</p>
                    </div>
                </div>
                <a href="laporan.php" class="bg-white text-[#2563EB] px-6 py-3 rounded-lg font-medium hover:bg-slate-100">Akses Laporan</a>
            </div>

            <div class="card overflow-hidden mb-5">
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="font-semibold text-lg">Permintaan Terbaru</h3>
                    <a href="admin_peminjaman.php" class="text-[#1E3A8A] text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr class="text-left">
                                <th class="px-5 py-4 text-sm font-semibold">Peminjam</th>
                                <th class="px-5 py-4 text-sm font-semibold">Barang</th>
                                <th class="px-5 py-4 text-sm font-semibold">Jumlah</th>
                                <th class="px-5 py-4 text-sm font-semibold">Waktu</th>
                                <th class="px-5 py-4 text-sm font-semibold">Status</th>
                                <th class="px-5 py-4 text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($permintaan)) { ?>
                            <tr class="table-row border-t">
                                <td class="px-5 py-4">
                                    <div class="font-medium"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($row['npm']) ?></div>
                                </td>
                                <td class="px-5 py-4"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                <td class="px-5 py-4 font-medium"><?= $row['jumlah'] ?></td>
                                <td class="px-5 py-4 text-slate-500 text-sm"><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                                <td class="px-5 py-4">
                                    <?php if($row['status'] == 'pending'){ ?>
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Menunggu</span>
                                    <?php } elseif($row['status'] == 'disetujui'){ ?>
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Disetujui</span>
                                    <?php } elseif($row['status'] == 'ditolak'){ ?>
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Ditolak</span>
                                    <?php } else { ?>
                                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Dipinjam</span>
                                    <?php } ?>
                                </td>
                                <td class="px-5 py-4">
                                    <?php if($row['status'] == 'pending'){ ?>
                                        <div class="flex gap-2">
                                            <a href="setujui_peminjaman.php?id=<?= $row['id'] ?>" class="px-3 py-1 rounded-md bg-green-100 text-green-700 text-xs font-semibold hover:bg-green-200">Setujui</a>
                                            <a href="tolak_peminjaman.php?id=<?= $row['id'] ?>" class="px-3 py-1 rounded-md bg-red-100 text-red-700 text-xs font-semibold hover:bg-red-200">Tolak</a>
                                        </div>
                                    <?php } else { ?>
                                        <span class="text-slate-400 text-sm">-</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card overflow-hidden mb-5">
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="font-semibold text-lg">Pengembalian Terbaru</h3>
                    <a href="admin_pengembalian.php" class="text-[#1E3A8A] text-sm font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Peminjam</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Barang</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Tanggal Pinjam</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Tanggal Kembali</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Status</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($pengembalian)) { ?>
                            <tr class="table-row border-t">
                                <td class="px-5 py-4">
                                    <div class="font-medium"><?= htmlspecialchars($row['nama']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($row['npm']) ?></div>
                                </td>
                                <td class="px-5 py-4"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                <td class="px-5 py-4 text-sm text-slate-600"><?= date('Y-m-d', strtotime($row['tanggal_pinjam'])) ?></td>
                                <td class="px-5 py-4 text-sm text-slate-600"><?= date('Y-m-d', strtotime($row['tanggal_pengembalian'])) ?></td>
                                <td class="px-5 py-4">
                                    <?php if($row['status'] == 'menunggu'){ ?>
                                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Menunggu Verifikasi</span>
                                    <?php } else { ?>
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Selesai</span>
                                    <?php } ?>
                                </td>
                                <td class="px-5 py-4">
                                    <?php if($row['status'] == 'menunggu'){ ?>
                                        <a href="verifikasi_pengembalian.php?id=<?= $row['id'] ?>" class="px-3 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold hover:bg-blue-200">Verifikasi</a>
                                    <?php } else { ?>
                                        <span class="text-slate-400 text-sm">-</span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2">
                    <div class="card overflow-hidden">
                        <div class="p-5 border-b">
                            <h3 class="font-semibold text-lg">Aktivitas Terbaru</h3>
                        </div>
                        <div class="divide-y">
                            <?php if(mysqli_num_rows($aktivitas) > 0) { 
                                while($row = mysqli_fetch_assoc($aktivitas)) { ?>
                                <div class="p-5 flex items-start gap-4 hover:bg-slate-50">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i data-lucide="activity" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium"><?= htmlspecialchars($row['nama']) ?></div>
                                        <div class="text-sm text-slate-500 mt-1"><?= htmlspecialchars($row['aktivitas']) ?></div>
                                        <div class="text-xs text-slate-400 mt-2"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></div>
                                    </div>
                                </div>
                            <?php } } else { ?>
                                <div class="p-10 text-center text-slate-500">Belum ada aktivitas.</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <a href="barang_tambah.php" class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-[#1E3A8A] text-white shadow-xl flex items-center justify-center hover:scale-105 transition">
        <i data-lucide="plus" class="w-6 h-6"></i>
    </a>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>