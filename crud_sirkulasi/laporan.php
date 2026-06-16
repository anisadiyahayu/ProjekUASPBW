<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'] ?? 'Admin'; // Fallback jika session kosong

$kategori = mysqli_query($conn, "
SELECT *
FROM categories
ORDER BY nama ASC
");

$lokasi = mysqli_query($conn, "
SELECT *
FROM locations
ORDER BY nama_lokasi ASC
");

$query = mysqli_query($conn, "
SELECT
items.*,
categories.nama AS kategori,
locations.nama_lokasi AS lokasi
FROM items
LEFT JOIN categories ON items.id_kategori = categories.id
LEFT JOIN locations ON items.id_lokasi = locations.id
ORDER BY items.id DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris</title>
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

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen">

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
                        <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
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
                        <a href="laporan.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium">
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
                    <a href="logout.php" class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91]">
                        <i data-lucide="log-out" class="w-5 h-5 text-white group-hover:text-red-500"></i>
                        <span class="group-hover:text-red-500">Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <div class="ml-64 flex-1">

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

            <main class="p-6">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-slate-800">Laporan Inventaris</h1>
                    <p class="text-slate-500">Buat dan kelola laporan inventaris laboratorium</p>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-6">

                    <div onclick="pilihLaporan('barang')" id="laporan_barang" class="card p-5 border-2 border-blue-700 cursor-pointer hover:shadow-lg transition">
                        <h3 class="font-semibold">Laporan Data Barang</h3>
                        <p class="text-sm text-slate-500">Laporan lengkap semua barang di laboratorium</p>
                    </div>

                    <div onclick="pilihLaporan('masuk')" id="laporan_masuk" class="card p-5 cursor-pointer hover:shadow-lg transition">
                        <h3 class="font-semibold">Laporan Barang Masuk</h3>
                        <p class="text-sm text-slate-500">Laporan barang yang masuk ke inventaris</p>
                    </div>

                    <div onclick="pilihLaporan('keluar')" id="laporan_keluar" class="card p-5 cursor-pointer hover:shadow-lg transition">
                        <h3 class="font-semibold">Laporan Barang Keluar</h3>
                        <p class="text-sm text-slate-500">Laporan barang yang keluar/dipinjam</p>
                    </div>

                    <div onclick="pilihLaporan('stok')" id="laporan_stok" class="card p-5 cursor-pointer hover:shadow-lg transition">
                        <h3 class="font-semibold">Laporan Stok Barang</h3>
                        <p class="text-sm text-slate-500">Laporan status stok barang saat ini</p>
                    </div>

                    <div onclick="pilihLaporan('permintaan')" id="laporan_permintaan" class="card p-5 cursor-pointer hover:shadow-lg transition">
                        <h3 class="font-semibold">Laporan Permintaan Barang</h3>
                        <p class="text-sm text-slate-500">Laporan riwayat permintaan peminjaman</p>
                    </div>

                </div>

                <div class="card p-5 mb-6">
                    <h3 class="font-semibold mb-4">Filter Laporan</h3>
                    <div class="grid md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-sm block mb-1">Tanggal Mulai</label>
                            <input type="date" class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="text-sm block mb-1">Tanggal Akhir</label>
                            <input type="date" class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="text-sm block mb-1">Kategori Barang</label>
                            <select class="w-full border rounded-lg px-4 py-2">
                                <option value="">Semua Kategori</option>
                                <?php
                                $kategori_data = [];
                                while ($k = mysqli_fetch_assoc($kategori)) :
                                    $kategori_data[] = $k;
                                ?>
                                    <option value="<?= htmlspecialchars($k['id']) ?>"><?= htmlspecialchars($k['nama']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm block mb-1">Lokasi Penyimpanan</label>
                            <select class="w-full border rounded-lg px-4 py-2">
                                <option value="">Semua Lokasi</option>
                                <?php
                                $lokasi_data = [];
                                while ($l = mysqli_fetch_assoc($lokasi)) :
                                    $lokasi_data[] = $l;
                                ?>
                                    <option value="<?= htmlspecialchars($l['id']) ?>"><?= htmlspecialchars($l['nama_lokasi']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card p-5 mb-6">
                    <h3 class="font-semibold mb-4">Aksi Laporan</h3>
                    <div class="grid md:grid-cols-4 gap-4">
                        <button onclick="previewLaporan()" class="bg-blue-50 text-blue-600 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-blue-100 transition">
                            <i data-lucide="eye"></i> Preview Laporan
                        </button>
                        <button onclick="window.print()" class="bg-green-50 text-green-600 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-green-100 transition">
                            <i data-lucide="printer"></i> Cetak Laporan
                        </button>
                        <a href="laporan_pdf.php" class="bg-red-50 text-red-600 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-red-100 transition">
                            <i data-lucide="file-down"></i> Export PDF
                        </a>
                        <a href="laporan_excel.php" class="bg-emerald-50 text-emerald-600 py-3 rounded-xl font-medium flex items-center justify-center gap-2 hover:bg-emerald-100 transition">
                            <i data-lucide="sheet"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold mb-4">Preview Laporan</h3>
                    <div id="previewArea">

                        <div id="previewPlaceholder" class="bg-slate-50 rounded-xl p-16 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-slate-200 flex items-center justify-center">
                                <i data-lucide="file-text" class="w-10 h-10 text-slate-500"></i>
                            </div>
                            <h4 class="font-semibold text-slate-700 mb-2">Preview laporan akan ditampilkan di sini</h4>
                            <p class="text-slate-500 text-sm">Pilih rentang tanggal dan filter yang sesuai, kemudian klik Preview</p>
                        </div>

                        <div id="tablePreview" class="hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b bg-slate-50">
                                            <th class="p-3 text-left text-sm font-semibold text-slate-700">Kode</th>
                                            <th class="p-3 text-left text-sm font-semibold text-slate-700">Nama Barang</th>
                                            <th class="p-3 text-left text-sm font-semibold text-slate-700">Kategori</th>
                                            <th class="p-3 text-left text-sm font-semibold text-slate-700">Lokasi</th>
                                            <th class="p-3 text-center text-sm font-semibold text-slate-700">Stok</th>
                                            <th class="p-3 text-left text-sm font-semibold text-slate-700">Kondisi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                            <tr class="border-b hover:bg-slate-50 transition">
                                                <td class="p-3 text-sm"><?= htmlspecialchars($row['kode_barang'] ?? '-') ?></td>
                                                <td class="p-3 text-sm"><?= htmlspecialchars($row['nama_barang'] ?? '-') ?></td>
                                                <td class="p-3 text-sm"><?= htmlspecialchars($row['kategori'] ?? 'Tidak ada kategori') ?></td>
                                                <td class="p-3 text-sm"><?= htmlspecialchars($row['lokasi'] ?? 'Tidak ada lokasi') ?></td>
                                                <td class="p-3 text-center font-semibold text-sm"><?= htmlspecialchars($row['stok'] ?? '0') ?></td>
                                                <td class="p-3">
                                                    <?php
                                                    $kondisi = $row['kondisi'] ?? '';
                                                    $kondisi_labels = [
                                                        'baik' => ['label' => 'Baik', 'class' => 'bg-green-100 text-green-700'],
                                                        'rusak_ringan' => ['label' => 'Rusak Ringan', 'class' => 'bg-yellow-100 text-yellow-700'],
                                                        'rusak_berat' => ['label' => 'Rusak Berat', 'class' => 'bg-red-100 text-red-700']
                                                    ];

                                                    if (isset($kondisi_labels[$kondisi])) {
                                                        echo '<span class="px-3 py-1 rounded-full text-xs ' . $kondisi_labels[$kondisi]['class'] . '">' . $kondisi_labels[$kondisi]['label'] . '</span>';
                                                    } else {
                                                        echo '<span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">Tidak diketahui</span>';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();

        let jenisLaporan = 'barang';

        function pilihLaporan(jenis) {
            jenisLaporan = jenis;
            const cards = ['laporan_barang', 'laporan_masuk', 'laporan_keluar', 'laporan_stok', 'laporan_permintaan'];
            cards.forEach(id => {
                document.getElementById(id).classList.remove('border-2', 'border-blue-700');
            });
            document.getElementById('laporan_' + jenis).classList.add('border-2', 'border-blue-700');
        }

        function previewLaporan() {
            document.getElementById('previewPlaceholder').style.display = 'none';
            document.getElementById('tablePreview').classList.remove('hidden');
            console.log('Jenis laporan:', jenisLaporan);

            if (jenisLaporan === 'barang') {
            } else if (jenisLaporan === 'masuk') {
                alert('Fitur laporan barang masuk akan segera hadir');
            } else if (jenisLaporan === 'keluar') {
                alert('Fitur laporan barang keluar akan segera hadir');
            } else if (jenisLaporan === 'stok') {
                alert('Fitur laporan stok akan segera hadir');
            } else if (jenisLaporan === 'permintaan') {
                alert('Fitur laporan permintaan akan segera hadir');
            }

            lucide.createIcons();
        }
    </script>

</body>

</html>