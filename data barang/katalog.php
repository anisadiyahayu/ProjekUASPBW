<?php

include "auth.php";
include "koneksi.php";

$nama = $_SESSION['nama'];

$search = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi = $_GET['lokasi'] ?? '';
$kondisi = $_GET['kondisi'] ?? '';

$where = "WHERE 1=1";
if ($search != '') {
    $where .= " AND items.nama_barang LIKE '%$search%'";
}
if ($kategori != '') {
    $where .= " AND items.id_kategori='$kategori'";
}
if ($lokasi != '') {
    $where .= " AND items.id_lokasi='$lokasi'";
}
if ($kondisi != '') {
    $where .= " AND items.kondisi='$kondisi'";
}

$query = mysqli_query($conn, "
    SELECT
    items.*,
    categories.nama AS kategori,
    locations.nama_lokasi AS lokasi,
    laboratorium.nama_lab AS lab
    FROM items
    LEFT JOIN categories ON items.id_kategori = categories.id
    LEFT JOIN locations ON items.id_lokasi = locations.id
    LEFT JOIN laboratorium ON items.id_lab = laboratorium.id
    $where
    ORDER BY items.id DESC
");

$kategoriList = mysqli_query($conn, "
SELECT *
FROM categories
ORDER BY nama
");

$lokasiList = mysqli_query($conn, "
SELECT *
FROM locations
ORDER BY nama_lokasi
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="sidebar.css">
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
    <script>
        if (localStorage.getItem("mini_sidebar") === "collapsed") {
            document.documentElement.classList.add("mini-active");
        }
    </script>
</head>

<body>
    <div class="flex min-h-screen">
        <aside id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-[#1E3A8A] text-white transition-all duration-300 z-20">
            <div class="h-full flex flex-col">
                <div class="p-4 border-b border-blue-800">
                    <div class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 rounded-lg bg-[#3B82F6] flex items-center justify-center">
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
                            <a
                                href="dashboard_mahasiswa.php"
                                class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                                <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a
                                href="katalog.php"
                                class="bg-[#3B82F6] flex items-center gap-2 px-3 py-3 rounded-lg text-sm">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                Katalog Barang
                            </a>
                        </li>
                        <li>
                            <a
                                href="peminjaman.php"
                                class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                                Peminjaman Barang
                            </a>
                        </li>
                        <li>
                            <a
                                href="pengembalian.php"
                                class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                Pengembalian Barang
                            </a>
                        </li>
                        <li>
                            <a
                                href="riwayat.php"
                                class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                                <i data-lucide="history" class="w-4 h-4"></i>
                                Riwayat Peminjaman
                            </a>
                        </li>
                        <li>
                            <a
                                href="profil.php"
                                class="flex items-center gap-2 px-3 py-3 rounded-lg hover:bg-blue-800 text-sm">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="p-4 border-t border-blue-800">
                    <a
                        href="logout.php"
                        class="group flex items-center gap-3 px-4 py-4 rounded-xl hover:bg-[#4C3F91] transition-all duration-300">
                        <i
                            data-lucide="log-out"
                            class="w-5 h-5 text-white group-hover:text-red-500"></i>
                        <span class="font-medium text-white group-hover:text-red-500">
                            Logout
                        </span>
                    </a>
                </div>
            </div>
        </aside>

        <div id="main-content" class="ml-64 flex-1">
            <header class="bg-white border-b h-[60px] px-6 flex justify-between items-center sticky top-0 z-10">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="p-2 -ml-2 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                        <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="4" y1="6" x2="20" y2="6"></line>
                            <line x1="4" y1="12" x2="20" y2="12"></line>
                            <line x1="4" y1="18" x2="20" y2="18"></line>
                        </svg>
                        </button>
                </div>
                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <h4 class="text-sm font-semibold">
                                <?= htmlspecialchars($nama) ?>
                            </h4>
                            <p class="text-[11px] text-slate-500">
                                Mahasiswa
                            </p>
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center font-semibold">
                            <?= strtoupper(substr($nama, 0, 2)) ?>
                        </div>
                    </div>
                </div>
            </header>
            
            <main class="p-6 overflow-x-hidden">
                <div class="mb-6">
                    <h1 class="text-[20px] font-semibold text-slate-800">
                        Katalog Barang
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Jelajahi dan pinjam barang laboratorium yang tersedia
                    </p>
                </div>

                <div class="card p-5 mb-5">
                <form method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-sm font-medium block mb-2">Cari Barang</label>
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Nama atau kode barang" class="w-full border border-slate-200 rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-2">Kategori</label>
                            <select name="kategori" class="w-full border border-slate-200 rounded-lg px-4 py-2">
                                <option value="">Semua Kategori</option>
                                <?php while ($kat = mysqli_fetch_assoc($kategoriList)) : ?>
                                    <option value="<?= $kat['id'] ?>" <?= ($kategori == $kat['id']) ? 'selected' : '' ?>>
                                        <?= $kat['nama'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-2">Lokasi</label>
                            <select name="lokasi" class="w-full border border-slate-200 rounded-lg px-4 py-2">
                                <option value="">Semua Lokasi</option>
                                <?php while ($lok = mysqli_fetch_assoc($lokasiList)) : ?>
                                    <option value="<?= $lok['id'] ?>" <?= ($lokasi == $lok['id']) ? 'selected' : '' ?>>
                                        <?= $lok['nama_lokasi'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium block mb-2">Kondisi</label>
                            <select name="kondisi" class="w-full border border-slate-200 rounded-lg px-4 py-2">
                                <option value="">Semua Kondisi</option>
                                <option value="Baik" <?= ($kondisi == 'Baik') ? 'selected' : '' ?>>Baik</option>
                                <option value="Rusak Ringan" <?= ($kondisi == 'Rusak Ringan') ? 'selected' : '' ?>>Rusak Ringan</option>
                                <option value="Rusak Berat" <?= ($kondisi == 'Rusak Berat') ? 'selected' : '' ?>>Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="bg-[#1E3A8A] text-white px-5 py-2 rounded-lg">Filter</button>
                        <a href="katalog.php" class="border border-slate-200 px-5 py-2 rounded-lg hover:bg-slate-50">Reset</a>
                    </div>
                </form>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <?php while ($barang = mysqli_fetch_assoc($query)) : ?>
                        <div
                            class="card overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                            <div
                                class="h-36 bg-slate-100 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($barang['gambar'])) : ?>
                                    <img
                                        src="uploads/<?= $barang['gambar']; ?>"
                                        alt="<?= $barang['nama_barang']; ?>"
                                        class="w-full h-full object-cover">
                                <?php else : ?>
                                    <i
                                        data-lucide="package"
                                        class="w-14 h-14 text-slate-400"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div class="p-4">
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <span
                                        class="bg-blue-100 text-blue-700 text-[10px] px-2 py-1 rounded-md font-medium">
                                        <?= $barang['kategori']; ?>
                                    </span>
                                    
                                    <p class="text-xs text-slate-500 mb-3">
                                        <?= $barang['lokasi']; ?>
                                    </p>
                                </div>
                                <h3 class="font-semibold text-[18px] text-slate-800 mb-2">
                                    <?= $barang['nama_barang']; ?>
                                </h3>
                                
                                <p class="text-sm text-slate-500 mb-4 line-clamp-2"> <?= !empty($barang['deskripsi']) ? substr($barang['deskripsi'], 0, 80) . '...' : 'Tidak ada deskripsi'; ?> </p>

                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-xs text-slate-500">
                                        Stok
                                    </span>
                                    <?php if ($barang['stok'] > 0) : ?>
                                        <span
                                            class="bg-green-100 text-green-700 text-[11px] px-2 py-1 rounded-full">
                                            <?= $barang['stok']; ?> tersedia
                                        </span>
                                    <?php else : ?>
                                        <span
                                            class="bg-red-100 text-red-700 text-[11px] px-2 py-1 rounded-full">
                                            Habis
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center justify-between mb-5">
                                    <span class="text-xs text-slate-500">
                                        Kondisi
                                    </span>
                                    <?php
                                    if ($barang['kondisi'] == "baik") {
                                        echo '<span class="text-green-600 text-xs font-medium">Baik</span>';
                                    } elseif ($barang['kondisi'] == "rusak ringan") {
                                        echo '<span class="text-yellow-600 text-xs font-medium">Rusak Ringan</span>';
                                    } else {
                                        echo '<span class="text-red-600 text-xs font-medium">Rusak Berat</span>';
                                    }
                                    ?>
                                </div>

                                <div class="flex gap-2">
                                    <button type="button" onclick="openDetailBarang(
                                                '<?= addslashes($barang['kode_barang']) ?>',
                                                '<?= addslashes($barang['nama_barang']) ?>',
                                                '<?= addslashes($barang['kategori']) ?>',
                                                '<?= addslashes($barang['lokasi']) ?>',
                                                '<?= $barang['stok'] ?>',
                                                '<?= addslashes($barang['kondisi']) ?>',
                                                '<?= addslashes($barang['deskripsi']) ?>'
                                            )" class="flex-1 border border-slate-200 text-center py-2 rounded-lg text-sm hover:bg-slate-50">
                                        Lihat Detail
                                    </button>

                                    <button
                                        type="button"
                                        onclick='openPinjam(
                                            <?= $barang["id"] ?>,
                                            <?= json_encode($barang["nama_barang"]) ?>,
                                            <?= $barang["stok"] ?>
                                        )'
                                        class="flex-1 bg-[#1E3A8A] text-white text-center py-2 rounded-lg text-sm hover:bg-blue-700">
                                        Pinjam
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php
                mysqli_data_seek($query, 0);
                if (mysqli_num_rows($query) == 0):
                ?>
                    <div class="card p-10 text-center mt-6">
                        <div
                            class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <i
                                data-lucide="package-x"
                                class="w-10 h-10 text-slate-400"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-slate-700">
                            Barang Tidak Ditemukan
                        </h3>
                        <p class="text-sm text-slate-500 mt-2">
                            Tidak ada barang yang sesuai dengan pencarian.
                        </p>
                    </div>
                <?php endif; ?>

                <a
                    href="#"
                    class="fixed bottom-5 right-5 w-12 h-12 rounded-full bg-[#1E3A8A] text-white flex items-center justify-center shadow-lg hover:bg-blue-700 transition-all duration-300">
                    <i
                        data-lucide="menu"
                        class="w-5 h-5"></i>
                </a>
            </main>
        </div>

        <div id="modalDetail" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-xl overflow-hidden">
                <div class="px-6 py-5 border-b flex justify-between items-center">
                    <h2 class="text-2xl font-semibold">Detail Barang</h2>
                    <button onclick="closeDetailBarang()">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-slate-500">Kode Barang</p>
                            <h4 id="d_kode" class="font-semibold"></h4>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Nama Barang</p>
                            <h4 id="d_nama" class="font-semibold"></h4>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Kategori</p>
                            <h4 id="d_kategori" class="font-semibold"></h4>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Lokasi</p>
                            <h4 id="d_lokasi" class="font-semibold"></h4>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Stok</p>
                            <h4 id="d_stok" class="font-semibold"></h4>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Kondisi</p>
                            <h4 id="d_kondisi" class="font-semibold"></h4>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm text-slate-500 mb-2">Deskripsi</p>
                        <p id="d_deskripsi" class="text-slate-700"></p>
                    </div>
                    <div class="mt-8">
                        <p class="text-sm text-slate-500 mb-2">Status Stok</p>
                        <span id="d_badge" class="px-3 py-1 rounded-full text-xs font-medium">Aman</span>
                        <div class="w-full bg-slate-200 rounded-full h-3 mt-3">
                            <div id="d_progress" class="bg-green-500 h-3 rounded-full" style="width:70%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div
            id="modalPinjam"
            class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
                <form action="proses_pinjam.php" method="POST">
                    <input
                        type="hidden"
                        name="item_id"
                        id="pinjam_item_id">
                    <div class="p-5 border-b">
                        <h3 class="font-semibold text-xl">
                            Ajukan Peminjaman
                        </h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div>
                            <label class="block mb-2 text-sm">
                                Nama Barang
                            </label>
                            <input
                                type="text"
                                id="pinjam_nama_barang"
                                readonly
                                class="w-full border rounded-lg px-4 py-3 bg-slate-50">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm">
                                Jumlah Pinjam
                            </label>
                            <input
                                type="number"
                                name="jumlah"
                                id="pinjam_jumlah"
                                min="1"
                                required
                                class="w-full border rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm">
                                Keperluan
                            </label>
                            <textarea
                                name="keperluan"
                                required
                                rows="3"
                                class="w-full border rounded-lg px-4 py-3"></textarea>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm">
                                Tanggal Pinjam
                            </label>
                            <input
                                type="date"
                                name="tanggal_pinjam"
                                required
                                class="w-full border rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm">
                                Estimasi Kembali
                            </label>
                            <input
                                type="date"
                                name="tanggal_kembali"
                                required
                                class="w-full border rounded-lg px-4 py-3">
                        </div>
                    </div>
                    <div class="p-5 border-t flex justify-end gap-3">
                        <button
                            type="button"
                            onclick="closePinjam()"
                            class="px-5 py-2 border rounded-lg">
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="px-5 py-2 bg-[#1E3A8A] text-white rounded-lg">
                            Ajukan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
            lucide.createIcons();
            function openPinjam(id, nama, stok) {
                document.getElementById('pinjam_item_id').value = id;
                document.getElementById('pinjam_nama_barang').value = nama;
                let jumlah = document.getElementById('pinjam_jumlah');
                jumlah.max = stok;
                jumlah.value = 1;
                document
                    .getElementById('modalPinjam')
                    .classList.remove('hidden');
            }
            function closePinjam() {
                document
                    .getElementById('modalPinjam')
                    .classList.add('hidden');
            }
            function openDetailBarang(kode, nama, kategori, lokasi, stok, kondisi, deskripsi) {
                document.getElementById('d_kode').innerText = kode;
                document.getElementById('d_nama').innerText = nama;
                document.getElementById('d_kategori').innerText = kategori;
                document.getElementById('d_lokasi').innerText = lokasi;
                document.getElementById('d_stok').innerText = stok;
                document.getElementById('d_kondisi').innerText = kondisi;
                document.getElementById('d_deskripsi').innerText = deskripsi ? deskripsi : 'Tidak ada deskripsi';
                let badge = document.getElementById('d_badge');
                let progress = document.getElementById('d_progress');
                if (stok <= 0) {
                    badge.innerHTML = 'Stok Habis';
                    badge.className = 'px-3 py-1 rounded-full text-xs bg-red-100 text-red-600';
                    progress.style.width = '0%';
                    progress.className = 'bg-red-500 h-3 rounded-full';
                } else if (stok <= 5) {
                    badge.innerHTML = 'Stok Minimum';
                    badge.className = 'px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700';
                    progress.style.width = '20%';
                    progress.className = 'bg-yellow-500 h-3 rounded-full';
                } else {
                    badge.innerHTML = 'Aman';
                    badge.className = 'px-3 py-1 rounded-full text-xs bg-green-100 text-green-700';
                    progress.style.width = '70%';
                    progress.className = 'bg-green-500 h-3 rounded-full';
                }
                document.getElementById('modalDetail').classList.remove('hidden');
                lucide.createIcons();
            }
            function closeDetailBarang() {
                document.getElementById('modalDetail').classList.add('hidden');
            }
        </script>
    </div>
    <script src="script.js"></script>
</body>

</html>