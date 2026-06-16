<?php
require_once '../auth/role_check.php';
require_once '../include/koneksi.php';

$conn->query("UPDATE categories SET id = UUID() WHERE id = '' OR id IS NULL");
$conn->query("UPDATE locations SET id = UUID() WHERE id = '' OR id IS NULL");

$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$lokasi   = $_GET['lokasi'] ?? '';
$kondisi  = $_GET['kondisi'] ?? '';

$query = "SELECT i.id, i.kode, i.nama, i.stok, i.satuan, i.kondisi, i.keterangan, i.id_kategori, i.id_lokasi, i.foto_barang,
                 COALESCE(c.nama, '-') as nama_kategori, 
                 COALESCE(l.nama, '-') as nama_lokasi 
          FROM items i 
          LEFT JOIN categories c ON i.id_kategori = c.id 
          LEFT JOIN locations l ON i.id_lokasi = l.id 
          WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $query .= " AND (i.nama LIKE ? OR i.kode LIKE ?)";
    $params[] = "%".$search."%";
    $params[] = "%".$search."%";
    $types .= "ss";
}
if ($kategori !== '') {
    $query .= " AND i.id_kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}
if ($lokasi !== '') {
    $query .= " AND i.id_lokasi = ?";
    $params[] = $lokasi;
    $types .= "s";
}
if ($kondisi !== '') {
    $query .= " AND i.kondisi = ?";
    $params[] = $kondisi;
    $types .= "s";
}
$query .= " ORDER BY i.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$items = $stmt->get_result();

$kategori_data = [];
$q_kat = $conn->query("SELECT id, nama FROM categories ORDER BY nama ASC");
if ($q_kat) {
    while($row = $q_kat->fetch_assoc()) $kategori_data[] = $row;
}

$lokasi_data = [];
$q_lok = $conn->query("SELECT id, nama FROM locations ORDER BY nama ASC");
if ($q_lok) {
    while($row = $q_lok->fetch_assoc()) $lokasi_data[] = $row;
}

require_once '../include/header.php'; 
?>

<div class="space-y-6 max-w-full mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Barang Laboratorium</h1>
            <p class="text-slate-500 text-sm">Kelola semua barang dan peralatan laboratorium</p>
        </div>
        <button onclick="openModal('modalTambah')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#1e3b8a] text-white rounded-lg hover:bg-blue-900 transition-colors font-medium text-sm shadow-sm">
            <i data-feather="plus" class="w-4 h-4 text-white"></i> Tambah Barang
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 border border-slate-200">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari barang..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20" onchange="this.form.submit()" />
            </div>
            <select name="kategori" class="px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                <?php foreach($kategori_data as $k): ?>
                    <option value="<?= htmlspecialchars($k['id']) ?>" <?= $kategori == $k['id'] ? 'selected' : '' ?>><?= htmlspecialchars($k['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="lokasi" class="px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20" onchange="this.form.submit()">
                <option value="">Semua Lokasi</option>
                <?php foreach($lokasi_data as $l): ?>
                    <option value="<?= htmlspecialchars($l['id']) ?>" <?= $lokasi == $l['id'] ? 'selected' : '' ?>><?= htmlspecialchars($l['nama']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="kondisi" class="px-4 py-2.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20" onchange="this.form.submit()">
                <option value="">Semua Kondisi</option>
                <option value="Bagus" <?= $kondisi == 'Bagus' ? 'selected' : '' ?>>Baik / Bagus</option>
                <option value="Rusak" <?= $kondisi == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
            </select>
        </form>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <i data-feather="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="font-medium text-sm">
                <?php 
                    if($_GET['msg'] == 'tambah_sukses') echo "Data barang berhasil ditambahkan.";
                    elseif($_GET['msg'] == 'edit_sukses') echo "Data barang berhasil diperbarui.";
                    elseif($_GET['msg'] == 'hapus_sukses') echo "Data barang berhasil dihapus.";
                ?>
            </span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-slate-700 text-sm">
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">Kode Barang</th>
                        <th class="px-6 py-4 font-semibold">Nama Barang</th>
                        <th class="px-6 py-4 font-semibold">Kategori</th>
                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                        <th class="px-6 py-4 font-semibold text-center">Stok</th>
                        <th class="px-6 py-4 font-semibold">Satuan</th>
                        <th class="px-6 py-4 font-semibold">Kondisi</th>
                        <th class="px-6 py-4 font-semibold">Status Stok</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php 
                    if ($items && $items->num_rows > 0):
                        while($i = $items->fetch_assoc()): 
                            $isHampirHabis = $i['stok'] < 10;
                            $status_stok_bg = $isHampirHabis ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700';
                            $status_stok_text = $isHampirHabis ? 'Hampir Habis' : 'Aman';
                            $kondisi_text = $i['kondisi'] == 'Bagus' ? 'Baik' : 'Rusak';
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-slate-600 font-medium"><?= htmlspecialchars($i['kode']) ?></td>
                        <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($i['nama']) ?></td>
                        <td class="px-6 py-4 <?= $i['nama_kategori'] == '-' ? 'text-red-500 font-bold' : 'text-slate-600' ?>">
                            <?= htmlspecialchars($i['nama_kategori']) ?>
                        </td>
                        <td class="px-6 py-4 <?= $i['nama_lokasi'] == '-' ? 'text-red-500 font-bold' : 'text-slate-500' ?> text-xs">
                            <?= htmlspecialchars($i['nama_lokasi']) ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800 text-center"><?= htmlspecialchars($i['stok']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($i['satuan']) ?></td>
                        <td class="px-6 py-4 text-slate-600"><?= htmlspecialchars($kondisi_text) ?></td>
                        <td class="px-6 py-4">
                            <span class='inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold <?= $status_stok_bg ?>'><?= $status_stok_text ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick="openDetailModal('<?= htmlspecialchars(json_encode($i), ENT_QUOTES, 'UTF-8') ?>')" class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1.5 rounded-md transition-colors" title="Detail">
                                    <i data-feather="eye" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openEditModal('<?= htmlspecialchars(json_encode($i), ENT_QUOTES, 'UTF-8') ?>')" class="text-green-500 hover:text-green-700 hover:bg-green-50 p-1.5 rounded-md transition-colors" title="Edit">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </button>
                                <button onclick="openDeleteModal('<?= htmlspecialchars($i['id']) ?>', '<?= htmlspecialchars($i['nama'], ENT_QUOTES) ?>')" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-md transition-colors" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else:
                    ?>
                        <tr><td colspan="9" class="px-6 py-8 text-center text-slate-500">Data barang tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Tambah Barang Baru</h3>
            <button type="button" onclick="closeModal('modalTambah')" class="text-slate-400 hover:text-slate-600"><i data-feather="x"></i></button>
        </div>
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data" class="p-6">
            <div class="mb-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Barang</label>
                <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                    <input type="text" name="kode" required placeholder="BRG-XXX" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama" required placeholder="Nama barang" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="NONE" disabled selected>-- Pilih Kategori --</option>
                        <?php foreach($kategori_data as $k): ?>
                            <option value="<?= htmlspecialchars($k['id']) ?>"><?= htmlspecialchars($k['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <select name="id_lokasi" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="NONE" disabled selected>-- Pilih Lokasi --</option>
                        <?php foreach($lokasi_data as $l): ?>
                            <option value="<?= htmlspecialchars($l['id']) ?>"><?= htmlspecialchars($l['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stok</label>
                    <input type="number" name="stok" required value="0" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" required placeholder="Pcs/Unit" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                    <select name="kondisi" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="Bagus">Baik / Bagus</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" placeholder="Opsional..." class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm"></textarea>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModal('modalTambah')" class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg font-medium hover:bg-slate-50 transition-colors text-sm">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#1e3b8a] text-white rounded-lg font-medium hover:bg-blue-900 transition-colors text-sm">Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800">Edit Barang</h3>
            <button type="button" onclick="closeModal('modalEdit')" class="text-slate-400 hover:text-slate-600"><i data-feather="x"></i></button>
        </div>
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data" class="p-6">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Barang (Opsional)</label>
                    <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none">
                </div>
                <div class="text-center md:text-left">
                    <p class="text-xs font-semibold text-slate-500 mb-1">Foto Saat Ini:</p>
                    <img id="edit_preview_foto" src="" class="h-16 w-16 object-cover rounded-md border border-slate-200 hidden" alt="Pratinjau Foto">
                    <span id="edit_no_foto_text" class="text-xs text-slate-400 italic">Tidak ada foto</span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                    <input type="text" name="kode" id="edit_kode" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                    <input type="text" name="nama" id="edit_nama" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="id_kategori" id="edit_kategori" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="NONE" disabled>-- Pilih Kategori --</option>
                        <?php foreach($kategori_data as $k): ?>
                            <option value="<?= htmlspecialchars($k['id']) ?>"><?= htmlspecialchars($k['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi <span class="text-red-500">*</span></label>
                    <select name="id_lokasi" id="edit_lokasi" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="NONE" disabled>-- Pilih Lokasi --</option>
                        <?php foreach($lokasi_data as $l): ?>
                            <option value="<?= htmlspecialchars($l['id']) ?>"><?= htmlspecialchars($l['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Stok</label>
                    <input type="number" name="stok" id="edit_stok" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Satuan</label>
                    <input type="text" name="satuan" id="edit_satuan" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                    <select name="kondisi" id="edit_kondisi" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm bg-white">
                        <option value="Bagus">Baik / Bagus</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan</label>
                <textarea name="keterangan" id="edit_keterangan" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500/20 outline-none text-sm"></textarea>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg font-medium hover:bg-slate-50 transition-colors text-sm">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-[#1e3b8a] text-white rounded-lg font-medium hover:bg-blue-900 transition-colors text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalDetail" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl overflow-hidden relative">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800">Detail Barang</h3>
            <button type="button" onclick="closeModal('modalDetail')" class="text-slate-400 hover:text-slate-600"><i data-feather="x"></i></button>
        </div>
        <div class="p-6 max-h-[85vh] overflow-y-auto">
            <div class="mb-6 flex justify-center bg-slate-50 p-4 rounded-xl border border-dashed border-slate-200">
                <img id="detail_foto" src="" alt="Foto Barang" class="max-h-48 rounded-lg object-cover shadow-sm hidden">
                <div id="detail_no_foto" class="text-sm text-slate-400 italic flex items-center gap-1">
                    <i data-feather="image" class="w-4 h-4"></i> Tidak ada foto barang
                </div>
            </div>

            <div class="grid grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Kode Barang</p>
                    <p id="detail_kode" class="font-bold text-slate-800 text-base"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Nama Barang</p>
                    <p id="detail_nama" class="font-bold text-slate-800 text-base"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Kategori</p>
                    <p id="detail_kategori" class="font-bold text-slate-800 text-base"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Lokasi</p>
                    <p id="detail_lokasi" class="font-bold text-slate-800 text-base"></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Stok Tersedia</p>
                    <p class="font-bold text-slate-800 text-base"><span id="detail_stok"></span> <span id="detail_satuan" class="font-normal text-sm text-slate-500 ml-1"></span></p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-1">Kondisi Barang</p>
                    <p id="detail_kondisi" class="font-bold text-slate-800 text-base"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm font-semibold text-slate-500 mb-2">Keterangan / Deskripsi</p>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <p id="detail_keterangan" class="font-medium text-slate-700 text-sm leading-relaxed"></p>
                    </div>
                </div>
                <div class="col-span-2 mt-2">
                    <p class="text-sm font-semibold text-slate-500 mb-3">Status Indikator Stok</p>
                    <span id="detail_status_badge" class="inline-block px-3 py-1 rounded-full text-xs font-bold mb-3"></span>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div id="detail_progress" class="h-2.5 rounded-full transition-all duration-500" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalHapus" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-red-50">
            <h3 class="text-lg font-bold text-red-700">Konfirmasi Hapus</h3>
            <button type="button" onclick="closeModal('modalHapus')" class="text-slate-400 hover:text-slate-600"><i data-feather="x"></i></button>
        </div>
        <div class="p-6">
            <p class="text-slate-600 text-center mb-6">Apakah Anda yakin ingin menghapus barang <br><strong id="hapus_nama_barang" class="text-slate-800 text-lg"></strong>?<br>Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex items-center gap-3">
                <button type="button" onclick="closeModal('modalHapus')" class="flex-1 py-2.5 border border-slate-200 text-slate-600 rounded-lg font-medium hover:bg-slate-50 transition-colors text-sm">Batal</button>
                <form id="formHapus" action="proses_hapus.php" method="POST" class="flex-1">
                    <input type="hidden" name="id" id="hapus_id">
                    <button type="submit" class="w-full py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors shadow-sm text-sm">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function openEditModal(dataJson) {
        const data = JSON.parse(dataJson);
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_kode').value = data.kode;
        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit_kategori').value = data.id_kategori || 'NONE';
        document.getElementById('edit_lokasi').value = data.id_lokasi || 'NONE';
        document.getElementById('edit_stok').value = data.stok;
        document.getElementById('edit_satuan').value = data.satuan;
        document.getElementById('edit_kondisi').value = data.kondisi;
        document.getElementById('edit_keterangan').value = data.keterangan || '';

        // Handle Preview Foto di Modal Edit
        const previewImg = document.getElementById('edit_preview_foto');
        const noFotoText = document.getElementById('edit_no_foto_text');
        if (data.foto_barang) {
            previewImg.src = 'uploads/' + data.foto_barang;
            previewImg.classList.remove('hidden');
            noFotoText.classList.add('hidden');
        } else {
            previewImg.src = '';
            previewImg.classList.add('hidden');
            noFotoText.classList.remove('hidden');
        }

        openModal('modalEdit');
    }

    function openDetailModal(dataJson) {
        const data = JSON.parse(dataJson);
        document.getElementById('detail_kode').innerText = data.kode;
        document.getElementById('detail_nama').innerText = data.nama;
        document.getElementById('detail_kategori').innerText = data.nama_kategori;
        document.getElementById('detail_lokasi').innerText = data.nama_lokasi;
        document.getElementById('detail_stok').innerText = data.stok;
        document.getElementById('detail_satuan').innerText = data.satuan;
        document.getElementById('detail_kondisi').innerText = data.kondisi === 'Bagus' ? 'Baik / Bagus' : 'Rusak';
        document.getElementById('detail_keterangan').innerText = data.keterangan || 'Tidak ada deskripsi.';
        
        // Handle Preview Foto di Modal Detail
        const imgEl = document.getElementById('detail_foto');
        const noImgEl = document.getElementById('detail_no_foto');
        if (data.foto_barang) {
            imgEl.src = 'uploads/' + data.foto_barang;
            imgEl.classList.remove('hidden');
            noImgEl.classList.add('hidden');
        } else {
            imgEl.src = '';
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
        }

        const stokNum = parseInt(data.stok);
        const badge = document.getElementById('detail_status_badge');
        const progress = document.getElementById('detail_progress');
        
        if(stokNum < 10) {
            badge.innerText = 'Hampir Habis (' + stokNum + ' tersisa)';
            badge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold mb-3 bg-amber-100 text-amber-700 border border-amber-200';
            progress.className = 'h-2.5 rounded-full transition-all duration-500 bg-amber-400';
            progress.style.width = Math.min((stokNum/10)*100, 100) + '%';
        } else {
            badge.innerText = 'Stok Aman (' + stokNum + ' tersedia)';
            badge.className = 'inline-block px-3 py-1 rounded-full text-xs font-bold mb-3 bg-green-100 text-green-700 border border-green-200';
            progress.className = 'h-2.5 rounded-full transition-all duration-500 bg-green-500';
            progress.style.width = '100%';
        }
        openModal('modalDetail');
    }

    function openDeleteModal(id, nama) {
        document.getElementById('hapus_id').value = id;
        document.getElementById('hapus_nama_barang').innerText = nama;
        openModal('modalHapus');
    }
</script>

<?php require_once '../include/footer.php'; ?>