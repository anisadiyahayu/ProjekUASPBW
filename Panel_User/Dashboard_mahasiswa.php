<?php
include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'] ?? 'Mahasiswa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <?php $current_page = 'dashboard'; ?>
        <?php include '../template/sidebar.php'; ?>

        <div id="main-content" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include '../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Halo, <?= htmlspecialchars($nama) ?>! 👋</h1>
                        <p class="text-sm text-slate-500 mt-1">Selamat datang di sistem informasi inventaris dan peminjaman alat laboratorium.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="katalog.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="package-search"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Katalog Barang</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Cari dan pinjam alat lab</p>
                        </div>
                    </a>

                    <a href="peminjaman.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:amber-blue-300 transition-all flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="clipboard-list"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Status Peminjaman</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Pantau pengajuan alat</p>
                        </div>
                    </a>

                    <a href="riwayat.php" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-300 transition-all flex items-center gap-4 group">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i data-lucide="history"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Riwayat Transaksi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Catatan peminjaman selesai</p>
                        </div>
                    </a>
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