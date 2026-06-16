<?php
include "auth.php";
include "../include/koneksi.php";

$nama = $_SESSION['nama'] ?? 'Mahasiswa';
$search = $_GET['search'] ?? '';

$query = mysqli_query($conn,"
SELECT
    items.*,
    categories.nama AS kategori,
    locations.nama AS lokasi
FROM items
LEFT JOIN categories ON items.id_kategori = categories.id
LEFT JOIN locations ON items.id_lokasi = locations.id
WHERE items.nama LIKE '%$search%'
ORDER BY items.id DESC
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang</title>
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans">

    <div class="min-h-screen flex">
        <?php $current_page = 'katalog'; ?>
        <?php include '../template/sidebar.php'; ?>

        <div id="main-content" class="flex-1 min-w-0 flex flex-col transition-all duration-300 ml-64">
            <?php include '../template/header.php'; ?>

            <main class="p-6 flex-1 max-w-7xl w-full mx-auto space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Katalog Barang</h1>
                        <p class="text-sm text-slate-500 mt-1">Cari instrumen atau bahan laboratorium yang tersedia.</p>
                    </div>

                    <form action="" method="GET" class="relative w-full md:w-80">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama barang..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white shadow-sm">
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php while($barang = mysqli_fetch_assoc($query)): ?>
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
                        <div class="h-40 bg-slate-100 flex items-center justify-center p-4">
                            <?php if(!empty($barang['foto_barang'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($barang['foto_barang']) ?>" alt="<?= htmlspecialchars($barang['nama']) ?>" class="max-h-full object-contain">
                            <?php else: ?>
                                <i data-lucide="package" class="w-12 h-12 text-slate-300"></i>
                            <?php endif; ?>
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="text-xs font-semibold text-blue-600 mb-1"><?= htmlspecialchars($barang['kode']) ?></div>
                            <h3 class="font-bold text-slate-800 line-clamp-2 flex-1 mb-2"><?= htmlspecialchars($barang['nama']) ?></h3>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5 mb-1">
                                <i data-lucide="tag" class="w-3 h-3"></i> <?= htmlspecialchars($barang['kategori'] ?? 'Umum') ?>
                            </div>
                            <div class="text-xs text-slate-500 flex items-center gap-1.5 mb-4">
                                <i data-lucide="box" class="w-3 h-3"></i> Stok: <strong class="text-slate-800"><?= htmlspecialchars($barang['stok']) ?></strong> <?= htmlspecialchars($barang['satuan'] ?? 'pcs') ?>
                            </div>

                            <div class="flex gap-2 mt-auto">
                                <a href="peminjaman.php?id=<?= $barang['id']; ?>" class="flex-1 bg-[#1E3A8A] text-white text-center py-2 rounded-xl text-sm font-medium hover:bg-blue-800 transition-colors shadow-sm">
                                    Pinjam
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <?php 
                mysqli_data_seek($query, 0);
                if(mysqli_num_rows($query) == 0): 
                ?>
                <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm">
                    <div class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                        <i data-lucide="package-x" class="w-8 h-8 text-slate-400"></i>
                    </div>
                    <h3 class="font-bold text-lg text-slate-800">Barang Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
                </div>
                <?php endif; ?>

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