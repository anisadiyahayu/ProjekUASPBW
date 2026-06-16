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
    $where .= " AND (
    items.nama_barang LIKE '%$search%'
    OR items.kode_barang LIKE '%$search%'
    )";
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
laboratorium.nama_lab AS laboratorium
FROM items
LEFT JOIN categories
ON items.id_kategori = categories.id
LEFT JOIN locations
ON items.id_lokasi = locations.id
LEFT JOIN laboratorium
ON items.id_lab = laboratorium.id
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
    <title>Data Barang</title>

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

        .table-row:hover {
            background: #f8fafc;
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
                        <h1 class="font-semibold">Lab Inventory</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-2 py-4">
                <div class="space-y-1">
                    <a href="dashboard_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard
                    </a>
                    <a href="barang.php" class="bg-[#3B82F6] flex items-center gap-3 px-4 py-3 rounded-lg text-sm">
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
                        <i data-lucide="clipboard-list" class="w-4 h-4"></i>
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
                    <a href="aktivitas.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-800 text-sm">
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
                    <i data-lucide="log-out" class="w-5 h-5 group-hover:text-red-500"></i>
                    <span class="group-hover:text-red-500">Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <div class="ml-64">
        <header class="bg-white border-b h-[56px] px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button>
                    <i data-lucide="menu" class="w-5 h-5 text-slate-500"></i>
                </button>
                <div class="relative w-[300px]">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-slate-400"></i>
                    <input type="text" placeholder="Cari barang..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm">
                </div>
            </div>

            <div class="flex items-center gap-5">
                <div class="relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                        3
                    </span>
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
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-slate-800">Data Barang Laboratorium</h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola seluruh inventaris barang laboratorium</p>
                </div>
                <button type="button" onclick="openModalBarang()" class="bg-[#1E3A8A] hover:bg-[#16306d] text-white px-5 py-3 rounded-lg flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Tambah Barang
                </button>
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
                        <a href="barang.php" class="border border-slate-200 px-5 py-2 rounded-lg hover:bg-slate-50">Reset</a>
                    </div>
                </form>
            </div>

            <div class="card overflow-hidden">
                <div class="flex justify-between items-center p-5 border-b">
                    <h3 class="font-semibold text-lg">Daftar Barang</h3>
                    <div class="text-sm text-slate-500">
                        Total: <strong><?= mysqli_num_rows($query) ?></strong> Barang
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Kode</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Nama Barang</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Kategori</th>
                                <th class="px-5 py-4 text-left text-sm font-semibold">Lokasi</th>
                                <th class="px-5 py-4 text-center text-sm font-semibold">Stok</th>
                                <th class="px-5 py-4 text-center text-sm font-semibold">Satuan</th>
                                <th class="px-5 py-4 text-center text-sm font-semibold">Kondisi</th>
                                <th class="px-5 py-4 text-center text-sm font-semibold">Status</th>
                                <th class="px-5 py-4 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                <?php
                                $stok = (int)$row['stok'];
                                if ($stok <= 0) {
                                    $statusText = "Stok Habis";
                                    $statusClass = "bg-red-100 text-red-700";
                                } elseif ($stok <= 5) {
                                    $statusText = "Stok Minimum";
                                    $statusClass = "bg-orange-100 text-orange-700";
                                } elseif ($stok <= 10) {
                                    $statusText = "Hampir Habis";
                                    $statusClass = "bg-yellow-100 text-yellow-700";
                                } else {
                                    $statusText = "Aman";
                                    $statusClass = "bg-green-100 text-green-700";
                                }
                                ?>
                                <tr class="table-row border-t">
                                    <td class="px-5 py-4">
                                        <div class="font-medium"><?= htmlspecialchars($row['kode_barang']) ?></div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <?php if (!empty($row['gambar'])) : ?>
                                                <img src="uploads/<?= $row['gambar'] ?>" class="w-12 h-12 rounded-lg object-cover border">
                                            <?php else : ?>
                                                <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center">
                                                    <i data-lucide="package" class="w-5 h-5 text-slate-400"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-medium"><?= htmlspecialchars($row['nama_barang']) ?></div>
                                                <div class="text-xs text-slate-500"><?= htmlspecialchars($row['laboratorium']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4"><?= htmlspecialchars($row['kategori']) ?></td>
                                    <td class="px-5 py-4"><?= htmlspecialchars($row['lokasi']) ?></td>
                                    <td class="px-5 py-4 text-center font-semibold"><?= $row['stok'] ?></td>
                                    <td class="px-5 py-4 text-center">Unit</td>
                                    <td class="px-5 py-4 text-center">
                                        <?php
                                        if ($row['kondisi'] == "Baik") {
                                            echo '<span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">Baik</span>';
                                        } elseif ($row['kondisi'] == "Rusak Ringan") {
                                            echo '<span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-medium">Rusak Ringan</span>';
                                        } else {
                                            echo '<span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-medium">Rusak Berat</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" onclick="openDetailBarang(
                                                '<?= addslashes($row['kode_barang']) ?>',
                                                '<?= addslashes($row['nama_barang']) ?>',
                                                '<?= addslashes($row['kategori']) ?>',
                                                '<?= addslashes($row['lokasi']) ?>',
                                                '<?= $row['stok'] ?>',
                                                '<?= addslashes($row['kondisi']) ?>',
                                                '<?= addslashes($row['deskripsi']) ?>'
                                            )" class="text-blue-600 hover:text-blue-800">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>

                                            <button type="button" onclick='openEditBarang(
                                                <?= json_encode($row["id"]) ?>,
                                                <?= json_encode($row["kode_barang"]) ?>,
                                                <?= json_encode($row["nama_barang"]) ?>,
                                                <?= json_encode($row["id_kategori"]) ?>,
                                                <?= json_encode($row["id_lokasi"]) ?>,
                                                <?= json_encode($row["id_lab"]) ?>,
                                                <?= json_encode($row["stok"]) ?>,
                                                <?= json_encode($row["kondisi"]) ?>,
                                                <?= json_encode($row["deskripsi"]) ?>
                                            )' class="text-green-600 hover:text-green-800">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>

                                            <button type="button" onclick='openHapusBarang(
                                                <?= json_encode($row["id"]) ?>,
                                                <?= json_encode($row["nama_barang"]) ?>
                                            )' class="text-red-600 hover:text-red-800">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                            <?php if (mysqli_num_rows($query) == 0) : ?>
                                <tr>
                                    <td colspan="9" class="text-center py-16 text-slate-500">
                                        <i data-lucide="package-search" class="w-12 h-12 mx-auto mb-3 text-slate-400"></i>
                                        Tidak ada data barang ditemukan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="modalBarang" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden">
            <div class="px-6 py-5 border-b flex justify-between items-center">
                <h2 class="text-xl font-semibold">Tambah Barang Baru</h2>
                <button onclick="closeModalBarang()">
                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
                </button>
            </div>

            <form action="barang_tambah.php" method="POST" enctype="multipart/form-data">
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="text-sm font-medium block mb-2">Kode Barang</label>
                            <input type="text" name="kode_barang" required class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="BRG-001">
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" required class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="Nama Barang">
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Kategori</label>
                            <select name="id_kategori" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Kategori</option>
                                <?php
                                $kategoriModal = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
                                while ($k = mysqli_fetch_assoc($kategoriModal)) {
                                ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Lokasi</label>
                            <select name="id_lokasi" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Lokasi</option>
                                <?php
                                $lokasiModal = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama_lokasi ASC");
                                while ($l = mysqli_fetch_assoc($lokasiModal)) {
                                ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Laboratorium</label>
                            <select name="id_lab" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="">Pilih Laboratorium</option>
                                <?php
                                $labModal = mysqli_query($conn, "SELECT * FROM laboratorium ORDER BY nama_lab ASC");
                                while ($lab = mysqli_fetch_assoc($labModal)) {
                                ?>
                                    <option value="<?= $lab['id'] ?>"><?= $lab['nama_lab'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Stok</label>
                            <input type="number" name="stok" required min="0" class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="0">
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Kondisi</label>
                            <select name="kondisi" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium block mb-2">Upload Gambar</label>
                            <input type="file" name="gambar" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="text-sm font-medium block mb-2">Keterangan</label>
                        <textarea name="deskripsi" rows="4" class="w-full border border-slate-200 rounded-lg px-4 py-3" placeholder="Keterangan tambahan..."></textarea>
                    </div>
                </div>

                <div class="border-t px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeModalBarang()" class="px-6 py-3 border border-slate-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-[#1E3A8A] text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
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

    <div id="modalEdit" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
            <div class="px-6 py-5 border-b flex justify-between items-center">
                <h2 class="text-2xl font-semibold">Edit Barang</h2>
                <button onclick="closeEditBarang()">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="barang_edit.php" method="POST">
                <input type="hidden" name="id" id="e_id">
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm mb-2">Kode Barang</label>
                            <input type="text" name="kode_barang" id="e_kode" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" id="e_nama" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Kategori</label>
                            <select name="id_kategori" id="e_kategori" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <?php
                                $kategoriEdit = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
                                while ($k = mysqli_fetch_assoc($kategoriEdit)) {
                                ?>
                                    <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Lokasi</label>
                            <select name="id_lokasi" id="e_lokasi" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <?php
                                $lokasiEdit = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama_lokasi ASC");
                                while ($l = mysqli_fetch_assoc($lokasiEdit)) {
                                ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['nama_lokasi'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Laboratorium</label>
                            <select name="id_lab" id="e_lab" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <?php
                                $labEdit = mysqli_query($conn, "SELECT * FROM laboratorium ORDER BY nama_lab ASC");
                                while ($lab = mysqli_fetch_assoc($labEdit)) {
                                ?>
                                    <option value="<?= $lab['id'] ?>"><?= $lab['nama_lab'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Stok</label>
                            <input type="number" name="stok" id="e_stok" required class="w-full border border-slate-200 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm mb-2">Kondisi</label>
                            <select name="kondisi" id="e_kondisi" class="w-full border border-slate-200 rounded-lg px-4 py-3">
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="block text-sm mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="e_deskripsi" rows="4" class="w-full border border-slate-200 rounded-lg px-4 py-3"></textarea>
                    </div>
                </div>

                <div class="border-t px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditBarang()" class="px-6 py-3 border rounded-lg">Batal</button>
                    <button type="submit" class="px-6 py-3 bg-[#1E3A8A] text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalHapus" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-5 border-b flex justify-between items-center">
                <h2 class="text-2xl font-semibold">Konfirmasi Hapus</h2>
                <button onclick="closeHapusBarang()">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-6">
                <p class="text-slate-600">
                    Apakah Anda yakin ingin menghapus barang <strong id="hapusNamaBarang"></strong> ?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <form action="barang_hapus.php" method="POST" class="mt-6">
                    <input type="hidden" name="id" id="hapusIdBarang">
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeHapusBarang()" class="px-6 py-3 border border-slate-200 rounded-lg">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModalBarang() {
            document.getElementById('modalBarang').classList.remove('hidden');
        }

        function closeModalBarang() {
            document.getElementById('modalBarang').classList.add('hidden');
        }

        function openDetailBarang(kode, nama, kategori, lokasi, stok, kondisi, deskripsi) {
            document.getElementById('d_kode').innerText = kode;
            document.getElementById('d_nama').innerText = nama;
            document.getElementById('d_kategori').innerText = kategori;
            document.getElementById('d_lokasi').innerText = lokasi;
            document.getElementById('d_stok').innerText = stok;
            document.getElementById('d_kondisi').innerText = kondisi;
            document.getElementById('d_deskripsi').innerText = deskripsi;

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

        function openEditBarang(id, kode, nama, kategori, lokasi, lab, stok, kondisi, deskripsi) {
            document.getElementById('e_id').value = id;
            document.getElementById('e_kode').value = kode;
            document.getElementById('e_nama').value = nama;
            document.getElementById('e_kategori').value = kategori;
            document.getElementById('e_lokasi').value = lokasi;
            document.getElementById('e_lab').value = lab;
            document.getElementById('e_stok').value = stok;
            document.getElementById('e_kondisi').value = kondisi;
            document.getElementById('e_deskripsi').value = deskripsi;

            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function closeEditBarang() {
            document.getElementById('modalEdit').classList.add('hidden');
        }

        function openHapusBarang(id, nama) {
            document.getElementById('hapusIdBarang').value = id;
            document.getElementById('hapusNamaBarang').innerHTML = nama;
            document.getElementById('modalHapus').classList.remove('hidden');
        }

        function closeHapusBarang() {
            document.getElementById('modalHapus').classList.add('hidden');
        }
    </script>
</body>

</html>