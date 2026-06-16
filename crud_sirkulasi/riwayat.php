<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

// Query untuk mengambil seluruh riwayat peminjaman user beserta nama barangnya
$query = mysqli_query($conn, "
SELECT 
    peminjaman.*, 
    items.nama_barang 
FROM peminjaman 
LEFT JOIN items 
    ON peminjaman.item_id = items.id 
WHERE peminjaman.user_id = " . $_SESSION['id'] . "
ORDER BY peminjaman.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Peminjaman</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f1f5f9;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->

    <aside class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white">

        <div class="h-full flex flex-col">

            <div class="p-4 border-b border-blue-800">

                <div class="flex items-center gap-2">

                    <div class="w-8 h-8 rounded-lg bg-[#3B82F6] flex items-center justify-center">
                        <i data-lucide="box" class="w-4 h-4"></i>
                    </div>

                    <div>
                        <h1 class="font-semibold text-sm">
                            Lab Inventory
                        </h1>

                        <p class="text-[10px] text-blue-200">
                            Student Portal
                        </p>
                    </div>

                </div>

            </div>

            <nav class="flex-1 py-3">

                <ul class="space-y-1 px-2">

                    <li>
                        <a href="dashboard_mahasiswa.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="katalog.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="package" class="w-4 h-4"></i>
                            Katalog Barang
                        </a>
                    </li>

                    <li>
                        <a href="peminjaman.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            Peminjaman Barang
                        </a>
                    </li>

                    <li>
                        <a href="pengembalian.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Pengembalian Barang
                        </a>
                    </li>

                    <li>
                        <a href="riwayat.php" class="bg-[#3B82F6] flex items-center gap-2 px-3 py-3 rounded-lg text-sm">
                            <i data-lucide="history" class="w-4 h-4"></i>
                            Riwayat Peminjaman
                        </a>
                    </li>

                    <li>
                        <a href="profil.php" class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                            <i data-lucide="user" class="w-4 h-4"></i>
                            Profil Saya
                        </a>
                    </li>

                </ul>

            </nav>

            <div class="p-4 border-t border-blue-800">

                <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all duration-300">

                    <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>

                    <span class="font-medium text-white group-hover:text-red-500">
                        Logout
                    </span>

                </a>

            </div>

        </div>

    </aside>

    <!-- CONTENT -->

    <div class="ml-64">

        <header class="bg-white border-b h-[52px] px-6 flex justify-between items-center">

            <div class="flex items-center gap-4">

                <button>
                    <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
                </button>

                <div class="relative w-[270px]">

                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>

                    <input type="text" placeholder="Cari barang laboratorium..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">

                </div>

            </div>

            <div class="flex items-center gap-5">

                <div class="relative">

                    <i data-lucide="bell" class="w-5 h-5"></i>

                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                        2
                    </span>

                </div>

                <div class="flex items-center gap-3">

                    <div class="text-right">

                        <h4 class="text-sm font-semibold">
                            <?= $nama ?>
                        </h4>

                        <p class="text-[11px] text-slate-500">
                            Mahasiswa
                        </p>

                    </div>

                    <div class="w-9 h-9 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center text-sm font-semibold">
                        <?= strtoupper(substr($nama, 0, 2)); ?>
                    </div>

                </div>

            </div>

        </header>

        <main class="p-6">

            <div class="mb-6">

                <h1 class="text-[20px] font-semibold text-slate-800">
                    Riwayat Peminjaman
                </h1>

                <p class="text-sm text-slate-500">
                    Lihat dan pantau status seluruh pengajuan peminjaman barang Anda
                </p>

            </div>

            <div class="card overflow-hidden">

                <div class="px-5 py-4 border-b">

                    <h3 class="font-semibold">
                        Log Aktivitas Peminjaman
                    </h3>

                </div>

                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-5 py-4 text-left">Barang</th>
                                <th class="px-5 py-4 text-left">Jumlah</th>
                                <th class="px-5 py-4 text-left">Tanggal Pinjam</th>
                                <th class="px-5 py-4 text-left">Batas Kembali</th>
                                <th class="px-5 py-4 text-left">Keperluan</th>
                                <th class="px-5 py-4 text-center">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php while ($row = mysqli_fetch_assoc($query)) : ?>

                                <tr class="border-t hover:bg-slate-50">

                                    <td class="px-5 py-4 font-medium">
                                        <?= htmlspecialchars($row['nama_barang'] ?? 'Barang Terhapus'); ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= $row['jumlah']; ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= date('d M Y', strtotime($row['tanggal_pinjam'])); ?>
                                    </td>

                                    <td class="px-5 py-4">
                                        <?= date('d M Y', strtotime($row['tanggal_kembali'])); ?>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        <?= htmlspecialchars($row['keperluan'] ?: '-'); ?>
                                    </td>

                                    <td class="px-5 py-4 text-center">

                                        <?php
                                        // Badge kondisional berdasarkan status peminjaman dari database
                                        switch ($row['status']) {
                                            case 'pending':
                                                echo '<span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-medium inline-block w-28 text-center">Menunggu</span>';
                                                break;
                                            case 'disetujui':
                                                echo '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium inline-block w-28 text-center">Dipinjam</span>';
                                                break;
                                            case 'ditolak':
                                                echo '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium inline-block w-28 text-center">Ditolak</span>';
                                                break;
                                            case 'dikembalikan':
                                                echo '<span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium inline-block w-28 text-center">Selesai</span>';
                                                break;
                                        }
                                        ?>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                            <?php if (mysqli_num_rows($query) == 0): ?>

                                <tr>

                                    <td colspan="6" class="text-center py-10 text-slate-500">

                                        <i data-lucide="history" class="w-10 h-10 mx-auto mb-3 text-slate-300"></i>

                                        Belum ada riwayat aktivitas peminjaman barang.

                                    </td>

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