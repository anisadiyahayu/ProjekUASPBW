<?php

require_once __DIR__ . '/../auth/auth_check.php';
include  __DIR__ . "/../include/koneksi.php";
include __DIR__ . "/fungsi_stok.php";

$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';
$kondisi  = $_GET['kondisi'] ?? '';

$where = "";
if ($search != '') {
    $where .= " AND items.nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
if ($kategori != '') {
    $where .= " AND items.id_kategori = '" . mysqli_real_escape_string($conn, $kategori) . "'";
}
if ($lokasi != '') {
    $where .= " AND items.id_lokasi = '" . mysqli_real_escape_string($conn, $lokasi) . "'";
}
if ($kondisi != '') {
    $where .= " AND items.kondisi = '" . mysqli_real_escape_string($conn, $kondisi) . "'";
}

$sql = mysqli_query($conn, "
    SELECT items.*, categories.nama AS nama_kategori, locations.nama AS nama_lokasi
    FROM items
    LEFT JOIN categories ON categories.id = items.id_kategori
    LEFT JOIN locations ON locations.id = items.id_lokasi
    WHERE 1=1 $where ORDER BY items.nama ASC
");

$query_kat = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama ASC");
$query_lok = mysqli_query($conn, "SELECT * FROM locations ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Inventaris Barang Lab</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../include/style_tailwind.css">
    <link rel="stylesheet" href="../include/style_sidebar.css">
</head>

<body>
    <div class="min-h-screen bg-background">
        <?php $current_page = 'admin_inventaris_lab'; ?>
        <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab') {
            include __DIR__ . '/../template/sidebar_admin.php';
        } else {
            include __DIR__ . '/../template/sidebar.php';
        } ?>
        <div id="main-content" class="transition-all duration-300 ml-64">
            <?php include __DIR__ . '/../template/header.php'; ?>
            <div class="p-6 max-w-7xl mx-auto space-y-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-gray-200 pb-5 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Data Inventaris Barang</h2>
                        <p class="text-sm text-gray-500 mt-1">Manajemen aset, peralatan logistik, dan ketersediaan bahan laboratorium.</p>
                    </div>
                    <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab'): ?>
                        <button onclick="openModal('modalTambah')" class="inline-flex items-center gap-2 bg-blue-700 hover:bg-blue-600 text-white font-medium px-4 py-2.5 rounded-lg text-sm shadow-sm transition-all"><i class="fa-solid fa-plus"></i> Tambah Barang</button>
                    <?php endif; ?>
                </div>

                <form method="GET" action="data_barang.php">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-200/60 items-center">
                        <div class="relative col-span-1 sm:col-span-2 lg:col-span-1">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" name="search" class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" placeholder="Cari nama barang..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <select name="kategori" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-700">
                            <option value="">-- Semua Kategori --</option>
                            <?php while ($k = mysqli_fetch_assoc($query_kat)): ?>
                                <option value="<?= $k['id'] ?>" <?= $kategori == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                        <select name="lokasi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-700">
                            <option value="">-- Semua Lokasi --</option>
                            <?php while ($l = mysqli_fetch_assoc($query_lok)): ?>
                                <option value="<?= $l['id'] ?>" <?= $lokasi == $l['id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nama']) ?></option>
                            <?php endwhile; ?>
                        </select>
                        <select name="kondisi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-gray-700">
                            <option value="">-- Semua Kondisi --</option>
                            <option value="Bagus" <?= $kondisi == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                            <option value="Rusak" <?= $kondisi == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                        </select>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition-all border border-gray-300/50"><i class="fa-solid fa-filter"></i> Filter</button>
                    </div>
                </form>

                <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-200">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Kode</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Nama Barang</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Kategori</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Lokasi</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Stok</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Kondisi</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider">Status</th>
                                <th class="px-6 py-3.5 font-semibold text-gray-700 tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (mysqli_num_rows($sql) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($sql)):
                                    $status = statusStok((int)$row['stok'], (int)$row['stok_minimum']);
                                    $badgeClass = ($status == 'Tersedia') ? 'bg-green-50 text-green-700 border-green-200' : (($status == 'Menipis') ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-700 border-red-200');
                                ?>
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4 font-semibold text-gray-600 whitespace-nowrap"><?= htmlspecialchars($row['kode']) ?></td>
                                        <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($row['nama']) ?></td>
                                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['nama_lokasi']) ?></td>
                                        <td class="px-6 py-4 text-gray-600 whitespace-nowrap"><?= htmlspecialchars($row['stok']) ?> <?= htmlspecialchars($row['satuan']) ?></td>
                                        <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($row['kondisi']) ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap"><span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border <?= $badgeClass ?>"><?= $status ?></span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <button onclick="openDetailModal('<?= $row['id'] ?>')" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-900 mr-3 transition-colors focus:outline-none"><i class="fa-solid fa-eye"></i> Detail</button>

                                            <?php if ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'Aslab'): ?>
                                                <button onclick="openEdit(this)" 
                                                    data-id="<?= $row['id'] ?>"
                                                    data-kode="<?= htmlspecialchars($row['kode'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-id_kategori="<?= $row['id_kategori'] ?>"
                                                    data-id_lokasi="<?= $row['id_lokasi'] ?>"
                                                    data-stok="<?= $row['stok'] ?>"
                                                    data-satuan="<?= htmlspecialchars($row['satuan'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-kondisi="<?= $row['kondisi'] ?>"
                                                    data-stok_minimum="<?= $row['stok_minimum'] ?>"
                                                    data-foto="<?= htmlspecialchars($row['foto_barang'], ENT_QUOTES, 'UTF-8') ?>"
                                                    data-keterangan="<?= htmlspecialchars($row['keterangan'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                    class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-900 mr-3 transition-colors focus:outline-none"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                                
                                                <a href="javascript:void(0);" class="inline-flex items-center gap-1 text-red-600 hover:text-red-900 transition-colors" onclick="openDelete('<?= $row['id'] ?>', '<?= addslashes($row['nama']) ?>')"><i class="fa-solid fa-trash"></i> Hapus</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-gray-400 bg-gray-50/30">Tidak ada data barang ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="modalTambah" class="fixed inset-0 bg-black/50 z-50 hidden [&.active]:flex items-center justify-center p-4 transition-all">
                <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-plus text-blue-600 mr-2"></i>Tambah Barang Baru</h3>
                        <button class="text-gray-400 hover:text-gray-600 text-2xl font-semibold focus:outline-none" onclick="closeModal('modalTambah')">&times;</button>
                    </div>
                    <form action="proses_tambah_barang.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Barang</label>
                                <input type="text" name="kode" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Contoh: BRG-001" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
                                <input type="text" name="nama" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukkan nama alat / bahan" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                                <select name="id_kategori" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php mysqli_data_seek($query_kat, 0); while ($kat = mysqli_fetch_assoc($query_kat)): ?>
                                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Penempatan</label>
                                <select name="id_lokasi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                    <option value="">-- Pilih Lokasi --</option>
                                    <?php mysqli_data_seek($query_lok, 0); while ($lok = mysqli_fetch_assoc($query_lok)): ?>
                                        <option value="<?= $lok['id'] ?>"><?= htmlspecialchars($lok['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Awal</label>
                                <input type="number" name="stok" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="0" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan</label>
                                <input type="text" name="satuan" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Pcs / Unit / Botol" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi</label>
                                <select name="kondisi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="Bagus">Bagus</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Minimum (Peringatan)</label>
                                <input type="number" name="stok_minimum" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Batas minimal stok peringatan" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Barang</label>
                                <input type="file" name="foto_barang" accept="image/*" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan</label>
                                <textarea name="keterangan" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Catatan tambahan mengenai kondisi detail atau spesifikasi barang..."></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" class="px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 transition-all" onclick="closeModal('modalTambah')">Batal</button>
                            <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Barang</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalEdit" class="fixed inset-0 bg-black/50 z-50 hidden [&.active]:flex items-center justify-center p-4 transition-all">
                <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-pen-to-square text-amber-600 mr-2"></i>Edit Data Barang</h3>
                        <button class="text-gray-400 hover:text-gray-600 text-2xl font-semibold focus:outline-none" onclick="closeModal('modalEdit')">&times;</button>
                    </div>
                    <form id="formEditBarang" action="prose_edit_barang.php" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Barang</label>
                                <input type="text" id="edit_kode" name="kode" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Barang</label>
                                <input type="text" id="edit_nama" name="nama" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                                <select id="edit_id_kategori" name="id_kategori" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php mysqli_data_seek($query_kat, 0); while ($kat = mysqli_fetch_assoc($query_kat)): ?>
                                        <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Lokasi Penempatan</label>
                                <select id="edit_id_lokasi" name="id_lokasi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                                    <option value="">-- Pilih Lokasi --</option>
                                    <?php mysqli_data_seek($query_lok, 0); while ($lok = mysqli_fetch_assoc($query_lok)): ?>
                                        <option value="<?= $lok['id'] ?>"><?= htmlspecialchars($lok['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Saat Ini</label>
                                <input type="number" id="edit_stok" name="stok" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan</label>
                                <input type="text" id="edit_satuan" name="satuan" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi</label>
                                <select id="edit_kondisi" name="kondisi" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="Bagus">Bagus</option>
                                    <option value="Rusak">Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Minimum (Peringatan)</label>
                                <input type="number" id="edit_stok_minimum" name="stok_minimum" min="0" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Barang</label>
                                <div id="edit_foto_current_wrapper" class="text-xs text-gray-500 mb-1 bg-gray-50 p-2 rounded border border-gray-200 hidden">
                                    <i class="fa-solid fa-image text-gray-400 mr-1"></i> Berkas saat ini: <em id="edit_foto_current_name" class="font-medium text-gray-700"></em>
                                </div>
                                <input type="file" name="foto_barang" accept="image/*" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-gray-50 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
                                <textarea id="edit_keterangan" name="keterangan" rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" class="px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 transition-all" onclick="closeModal('modalEdit')">Batal</button>
                            <button type="submit" name="update" class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modalDetail" class="fixed inset-0 bg-black/50 z-50 hidden [&.active]:flex items-center justify-center p-4 transition-all">
                <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full h-[85vh] overflow-hidden border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-white shadow-sm z-10">
                        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-eye text-blue-600 mr-2"></i>Detail Informasi Barang</h3>
                        <button class="text-gray-400 hover:text-gray-600 text-2xl font-semibold focus:outline-none" onclick="closeModal('modalDetail')">&times;</button>
                    </div>
                    <div class="flex-1 bg-gray-50 overflow-hidden">
                        <iframe id="iframeDetail" src="" class="w-full h-full border-0"></iframe>
                    </div>
                </div>
            </div>

            <div id="modalHapus" class="fixed inset-0 bg-black/50 z-50 hidden [&.active]:flex items-center justify-center p-4 transition-all">
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden border border-gray-100">
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Konfirmasi Hapus Data</h3>
                        <button class="text-gray-400 hover:text-gray-600 text-2xl font-semibold focus:outline-none" onclick="closeModal('modalHapus')">&times;</button>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Apakah Anda yakin ingin menghapus data barang <strong id="hapusNamaBarang" class="text-red-600 font-semibold"></strong>? Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                        <button class="px-4 py-2 bg-white hover:bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 transition-all" onclick="closeModal('modalHapus')">Batal</button>
                        <a id="btnLinkHapus" href="#" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm"><i class="fa-solid fa-trash"></i> Ya, Hapus</a>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/feather-icons"></script>
        <script>
            feather.replace();
        </script>
        <script src="../include/script.js"></script>
        <script>
            function openModal(id) {
                document.getElementById(id).classList.add('active');
            }

            function closeModal(id) {
                document.getElementById(id).classList.remove('active');
            }

            function openDelete(id, nama) {
                document.getElementById('hapusNamaBarang').innerText = nama;
                document.getElementById('btnLinkHapus').href = 'hapus_barang.php?id=' + id;
                openModal('modalHapus');
            }

            // Fungsi Membuka Detail Secara Popup
            function openDetailModal(id) {
                document.getElementById('iframeDetail').src = 'detail_barang.php?id=' + id;
                openModal('modalDetail');
            }

            // Fungsi Membuka Edit Secara Popup Dinamis
            function openEdit(el) {
                const d = el.dataset;
                // Mengubah action form dinamis ke berkas pemroses asli bawaan Anda
                document.getElementById('formEditBarang').action = 'prose_edit_barang.php?id=' + d.id;
                
                // Isi input values berdasarkan data baris tabel
                document.getElementById('edit_kode').value = d.kode;
                document.getElementById('edit_nama').value = d.nama;
                document.getElementById('edit_id_kategori').value = d.id_kategori;
                document.getElementById('edit_id_lokasi').value = d.id_lokasi;
                document.getElementById('edit_stok').value = d.stok;
                document.getElementById('edit_satuan').value = d.satuan;
                document.getElementById('edit_kondisi').value = d.kondisi;
                document.getElementById('edit_stok_minimum').value = d.stok_minimum;
                
                // Menampilkan informasi berkas foto saat ini jika ada
                const fotoWrapper = document.getElementById('edit_foto_current_wrapper');
                if (d.foto && d.foto.trim() !== '') {
                    document.getElementById('edit_foto_current_name').innerText = d.foto;
                    fotoWrapper.classList.remove('hidden');
                } else {
                    fotoWrapper.classList.add('hidden');
                }
                
                document.getElementById('edit_keterangan').value = d.keterangan;
                openModal('modalEdit');
            }
        </script>
</body>

</html>